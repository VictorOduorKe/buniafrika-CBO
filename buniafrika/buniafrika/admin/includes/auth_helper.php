<?php

require_once __DIR__ . '/../../includes/bootstrap.php';
startAppSession();


function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}


function requireAdminLogin() {
    global $conn;
    
    if (!isAdminLoggedIn()) {
        $is_admin_folder = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false);
        $redirect_path = $is_admin_folder ? '../login.php' : 'login.php';
        header("Location: " . $redirect_path);
        exit;
    }
    
    $timeout_seconds = 900;
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_seconds)) {
        if (isset($conn) && function_exists('logActivity')) {
            $admin_id = $_SESSION['admin_id'] ?? null;
            $admin_username = $_SESSION['admin_username'] ?? 'Unknown Admin';
            logActivity($conn, 'Admin Auto-Logout', "Admin logged out due to inactivity.", 'admin', $admin_id, $admin_username);
        }
        
        session_unset();
        session_destroy();
        
        $is_admin_folder = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false);
        $redirect_path = $is_admin_folder ? '../login.php?timeout=1' : 'login.php?timeout=1';
        header("Location: " . $redirect_path);
        exit;
    }
    
    $_SESSION['last_activity'] = time();
}
