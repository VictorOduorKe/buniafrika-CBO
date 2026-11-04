<?php
$page_title = 'Administrator Login | BuniAfrika CBO';
$meta_desc = 'Authorised BuniAfrika CBO administrators can sign in to manage website content.';

require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

if (isAdminLoggedIn()) {
    header("Location: admin/admin_dashboard.php");
    exit;
}

$error_message = '';
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $error_message = 'Your session has expired due to 15 minutes of inactivity. Please log in again.';
}
$locked_out = false;
$lockout_remaining = 0;

$ip_address = $_SERVER['HTTP_CF_CONNECTING_IP'] 
    ?? (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? trim(explode(',', (string)$_SERVER['HTTP_X_FORWARDED_FOR'])[0]) : null)
    ?? ($_SERVER['REMOTE_ADDR'] ?? '');
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

$conn->query("DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");

$failed_attempts = [];
$sql_check = "SELECT UNIX_TIMESTAMP(attempted_at) AS attempt_time FROM login_attempts WHERE ip_address = ? AND user_agent = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE) ORDER BY attempted_at ASC";
$stmt_check = $conn->prepare($sql_check);
if ($stmt_check) {
    $stmt_check->bind_param("ss", $ip_address, $user_agent);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    while ($row = $result_check->fetch_assoc()) {
        $failed_attempts[] = (int) $row['attempt_time'];
    }
    $stmt_check->close();
}

$attempt_count = count($failed_attempts);
if ($attempt_count >= 5) {
    $oldest_attempt_time = $failed_attempts[0];
    $lockout_expires = $oldest_attempt_time + 600;

    $db_time_res = $conn->query("SELECT UNIX_TIMESTAMP(NOW())");
    $current_db_time = $db_time_res ? (int) $db_time_res->fetch_row()[0] : time();

    $lockout_remaining = $lockout_expires - $current_db_time;
    if ($lockout_remaining > 0) {
        $locked_out = true;
        $next_trial_formatted = date('H:i:s', $lockout_expires);
        $minutes = ceil($lockout_remaining / 60);
        $error_message = "Too many failed attempts. Locked out. Please try again in {$minutes} minutes (Allowed after: {$next_trial_formatted}).";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rateCheck = checkRateLimit($conn, 'admin_login', 5, 900, 900);
    if (!$rateCheck['allowed']) {
        $locked_out = true;
        $error_message = "Too many login attempts. Please wait " . ($rateCheck['retry_after'] ?? 900) . " seconds before trying again.";
    }

    $csrf_token = $_POST['csrf_token'] ?? '';
    if (empty($error_message) && !verifyCsrfToken($csrf_token)) {
        logActivity($conn, 'CSRF Token Missing', 'A form submission failed due to missing or invalid CSRF token from IP: ' . ($ip_address ?? ''), 'system', null, 'System');
        $error_message = "Security validation failed. Refresh the page and try again.";
    } elseif ($locked_out) {
        $error_message = "Too many failed attempts. Lockout active.";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error_message = 'Please enter both username and password.';
        } else {
            $sql = "SELECT id, username, email, password FROM admins WHERE username = ? OR email = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ss", $username, $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows === 1) {
                    $admin = $result->fetch_assoc();
                    if (password_verify($password, $admin['password'])) {
                        $stmt_clear = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = ? AND user_agent = ?");
                        if ($stmt_clear) {
                            $stmt_clear->bind_param("ss", $ip_address, $user_agent);
                            $stmt_clear->execute();
                            $stmt_clear->close();
                        }

                        session_regenerate_id(true);
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['last_activity'] = time();

                        logActivity($conn, 'Admin Login Success', "Admin logged in successfully", 'admin', $admin['id'], $admin['username']);

                        header("Location: admin/admin_dashboard.php");
                        exit;
                    }
                }

                $stmt_log = $conn->prepare("INSERT INTO login_attempts (ip_address, user_agent) VALUES (?, ?)");
                if ($stmt_log) {
                    $stmt_log->bind_param("ss", $ip_address, $user_agent);
                    $stmt_log->execute();
                    $stmt_log->close();
                }
                logActivity($conn, 'Admin Login Failed', "Failed login attempt for username: " . $username, 'system', null, 'System');


                $failed_attempts[] = time();

                if (count($failed_attempts) === 3) {
                    require_once __DIR__ . '/includes/mailer.php';
                    $subject = "Security Alert: Multiple Failed Login Attempts";
                    $body = "<h3>Security Alert</h3><p>There have been 3 failed login attempts to the Admin Portal.</p><p><strong>IP Address:</strong> " . htmlspecialchars($ip_address) . "</p><p><strong>User Agent:</strong> " . htmlspecialchars($user_agent) . "</p>";
                    sendAdminEmail($subject, $body);
                }

                if (count($failed_attempts) >= 5) {
                    $locked_out = true;
                    $lockout_remaining = 600;
                    $next_trial_formatted = date('H:i:s', time() + 600);
                    $error_message = "Too many failed attempts. Locked out. Please try again in 10 minutes (Allowed after: {$next_trial_formatted}).";
                } else {
                    $remaining_attempts = 5 - count($failed_attempts);
                    $error_message = "Invalid username or password. You have {$remaining_attempts} attempts remaining.";
                }
            } else {
                $error_message = 'Database error. Please try again later.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Login Section -->
<section class="section container"
    style="max-width: 500px; padding-top: 80px; padding-bottom: 80px; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div class="bento-card"
        style="box-shadow: var(--shadow-xl); padding: var(--spacing-xl); width: 100%; border-radius: var(--radius-xl); border: 1px solid var(--outline-variant); background: var(--surface);">
        <div style="text-align: center; margin-bottom: var(--spacing-lg);">
            <span class="material-symbols-outlined"
                style="font-size: 48px; color: var(--primary); margin-bottom: var(--spacing-xs);">admin_panel_settings</span>
            <h1 style="font-size: 26px; font-weight: 700; margin-bottom: var(--spacing-xs); color: var(--primary);">
                Admin Portal</h1>
            <p style="font-size: 14px; color: var(--on-surface-variant);">Access limited to authorized NGO personnel.
            </p>
        </div>

        <?php
        $session_success = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
        unset($_SESSION['success_msg']);
        if (!empty($session_success)): ?>
            <div class="alert alert-success" style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">check_circle</span>
                <span><?php echo h($session_success); ?></span>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.BuniAlert) {
                    window.BuniAlert.success('Success', <?php echo json_encode($session_success); ?>);
                }
            });
            </script>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"
                style="margin-bottom: var(--spacing-md); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">error</span>
                <span class="alert-message-text"><?php echo h($error_message); ?></span>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.BuniAlert) {
                    window.BuniAlert.error('Authentication Notice', <?php echo json_encode($error_message); ?>);
                }
            });
            </script>
        <?php endif; ?>

        <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: var(--spacing-md);">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <div class="form-group" style="display: flex; flex-direction: column; gap: var(--spacing-xs);">
                <label class="form-label" for="username"
                    style="font-weight: 600; font-size: 14px; color: var(--on-surface);">Username or Email</label>
                <input class="form-control" type="text" id="username" name="username" placeholder="Enter username or email"
                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); font-family: var(--font-body); font-size: 14px; background: var(--background); color: var(--on-background);"
                    required autofocus <?php echo $locked_out ? 'disabled style="opacity: 0.6; cursor: not-allowed;"' : ''; ?> />
            </div>

            <div class="form-group"
                style="display: flex; flex-direction: column; gap: var(--spacing-xs); position: relative;">
                <label class="form-label" for="password"
                    style="font-weight: 600; font-size: 14px; color: var(--on-surface);">Password</label>
                <div style="position: relative; width: 100%;">
                    <input class="form-control" type="password" id="password" name="password"
                        placeholder="Enter password"
                        style="width: 100%; padding: 12px 48px 12px 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); font-family: var(--font-body); font-size: 14px; background: var(--background); color: var(--on-background);"
                        required <?php echo $locked_out ? 'disabled style="opacity: 0.6; cursor: not-allowed;"' : ''; ?> />
                    <button type="button" id="password-toggle"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--on-surface-variant); display: flex; align-items: center; justify-content: center; padding: 4px;"
                        aria-label="Toggle Password Visibility" <?php echo $locked_out ? 'disabled' : ''; ?>>
                        <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; padding: 14px 24px; font-size: 14px; font-weight: 700; border-radius: var(--radius-full); margin-top: var(--spacing-xs); background: var(--primary); color: var(--on-primary); transition: background-color 0.2s; <?php echo $locked_out ? 'opacity: 0.5; cursor: not-allowed;' : ''; ?>"
                <?php echo $locked_out ? 'disabled' : ''; ?>>
                Secure Log In
            </button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const toggleIcon = document.getElementById('toggle-icon');

        if (passwordToggle && passwordInput && toggleIcon) {
            passwordToggle.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
            });
        }
    });
</script>

<?php if ($locked_out): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let remainingSeconds = <?php echo $lockout_remaining; ?>;
            const submitBtn = document.querySelector('button[type="submit"]');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const alertSpan = document.querySelector('.alert-message-text');
            const toggleBtn = document.getElementById('password-toggle');

            if (toggleBtn) {
                toggleBtn.disabled = true;
            }

            const updateTimer = () => {
                if (remainingSeconds <= 0) {
                    clearInterval(interval);
                    if (usernameInput) {
                        usernameInput.disabled = false;
                        usernameInput.style.opacity = '1';
                        usernameInput.style.cursor = 'text';
                    }
                    if (passwordInput) {
                        passwordInput.disabled = false;
                        passwordInput.style.opacity = '1';
                        passwordInput.style.cursor = 'text';
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                    if (toggleBtn) {
                        toggleBtn.disabled = false;
                    }
                    if (alertSpan) {
                        alertSpan.parentElement.style.display = 'none';
                    }
                    window.location.reload();
                } else {
                    const minutes = Math.floor(remainingSeconds / 60);
                    const seconds = remainingSeconds % 60;
                    let timeStr = "";
                    if (minutes > 0) {
                        timeStr += minutes + "m ";
                    }
                    timeStr += seconds + "s";
                    if (alertSpan) {
                        alertSpan.innerHTML = `Too many failed login attempts. Locked out. Please try again in <strong>${timeStr}</strong>.`;
                    }
                }
            };

            const interval = setInterval(() => {
                remainingSeconds--;
                updateTimer();
            }, 1000);

            updateTimer();
        });
    </script>
<?php endif; ?>

<?php
require_once __DIR__ . '/includes/footer.php';
?>