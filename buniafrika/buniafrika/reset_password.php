<?php
$page_title = 'Reset Administrator Password | BuniAfrika CBO';
$meta_desc = 'Reset your administrator password.';

require_once __DIR__ . '/includes/bootstrap.php';
startAppSession();

require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

$error_message = '';
$success_message = '';

$token = $_GET['token'] ?? ($_POST['token'] ?? '');

if (empty($token)) {
    http_response_code(400);
    $error_message = "Invalid or missing password reset token. Please use the reset link sent to your email.";
} else {
    $stmt = $conn->prepare("SELECT id, username FROM admins WHERE reset_token = ? AND reset_expires > NOW() LIMIT 1");
if ($stmt) {
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rateCheck = checkRateLimit($conn, 'password_reset', 4, 900, 900);
            if (!$rateCheck['allowed']) {
                $error_message = 'Too many password reset attempts. Please wait ' . ($rateCheck['retry_after'] ?? 900) . ' seconds before trying again.';
                http_response_code(429);
            } else {
                $csrf_token = $_POST['csrf_token'] ?? '';
                if (!verifyCsrfToken($csrf_token)) {
                    logActivity($conn, 'CSRF Token Missing', 'A form submission failed due to missing or invalid CSRF token from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'system', null, 'System');
                    $error_message = 'Security validation failed. Refresh the page and try again.';
                    http_response_code(403);
                } else {
                    $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($new_password) || empty($confirm_password)) {
                $error_message = 'Please enter and confirm your new password.';
            } elseif ($new_password !== $confirm_password) {
                $error_message = 'Passwords do not match.';
            } elseif (strlen($new_password) < 8) {
                $error_message = 'Password must be at least 8 characters long.';
            } elseif (!preg_match('/[A-Z]/', $new_password)) {
                $error_message = 'Password must contain at least one uppercase letter.';
            } elseif (!preg_match('/[a-z]/', $new_password)) {
                $error_message = 'Password must contain at least one lowercase letter.';
            } elseif (!preg_match('/[0-9]/', $new_password)) {
                $error_message = 'Password must contain at least one number.';
            } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
                $error_message = 'Password must contain at least one special character.';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_stmt = $conn->prepare("UPDATE admins SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                if ($update_stmt) {
                    $update_stmt->bind_param("si", $hashed_password, $admin['id']);
                    if ($update_stmt->execute()) {
                        $success_message = 'Your password has been successfully reset. You can now login.';
                    } else {
                        $error_message = 'Failed to update password. Please try again later.';
                    }
                    $update_stmt->close();
                } else {
                    $error_message = 'Database error during update.';
                }
                }
            }
        }
    }
} else {
        $error_message = 'Invalid or expired password reset token.';
    }
    $stmt->close();
} else {
    $error_message = 'Database error verifying token.';
}
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section container" style="max-width: 500px; padding-top: 80px; padding-bottom: 80px; min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center;">
    <div class="bento-card" style="box-shadow: var(--shadow-xl); padding: var(--spacing-xl); width: 100%; border-radius: var(--radius-xl); border: 1px solid var(--outline-variant); background: var(--surface);">
        <div style="text-align: center; margin-bottom: var(--spacing-lg);">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--primary); margin-bottom: var(--spacing-xs);">lock_reset</span>
            <h1 style="font-size: 26px; font-weight: 700; margin-bottom: var(--spacing-xs); color: var(--primary);">Reset Password</h1>
            <p style="font-size: 14px; color: var(--on-surface-variant);">Enter your new password below.</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">check_circle</span>
                <span><?php echo h($success_message); ?></span>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.BuniAlert) {
                    window.BuniAlert.success('Password Reset Successful', <?php echo json_encode($success_message); ?>);
                }
            });
            </script>
            <div style="text-align: center; margin-top: 20px;">
                <a href="login.php" class="btn btn-primary" style="display: inline-block;">Go to Login</a>
            </div>
        <?php else: ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" style="margin-bottom: var(--spacing-md); display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">error</span>
                    <span class="alert-message-text"><?php echo h($error_message); ?></span>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.BuniAlert) {
                        window.BuniAlert.error('Password Reset', <?php echo json_encode($error_message); ?>);
                    }
                });
                </script>
            <?php endif; ?>

            <?php if (empty($error_message) || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <form method="POST" action="reset_password.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="token" value="<?php echo h($token); ?>">
                    
                    <div class="form-group" style="margin-bottom: var(--spacing-md);">
                        <label for="new_password" class="form-label">New Password</label>
                        <div style="position: relative;">
                            <span class="material-symbols-outlined" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--on-surface-variant);">lock</span>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="••••••••" required style="padding-left: 48px;">
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: var(--spacing-md);">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div style="position: relative;">
                            <span class="material-symbols-outlined" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--on-surface-variant);">lock</span>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••" required style="padding-left: 48px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px; padding: 14px;">
                        <span class="material-symbols-outlined">save</span> Reset Password
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>