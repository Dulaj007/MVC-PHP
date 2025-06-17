<?php
// 🔐 0. Extra secure INI settings (before session_start)
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

// 🔐 1. Secure cookie config
session_set_cookie_params([
    'lifetime' => 36000,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

// 🔐 2. Bind session to browser/IP
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
} elseif (
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] ||
    $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']
) {
    session_unset();
    session_destroy();
    session_start();
    exit;
}

// 🔐 3. Session fixation protection
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 🔄 4. Regenerate session ID every 30 minutes
if (!isset($_SESSION['last_regen'])) {
    $_SESSION['last_regen'] = time();
} elseif (time() - $_SESSION['last_regen'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}

// ⏱ 5. Logout after 10 hours idle
if (isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity']) > 36000) {
    session_unset();
    session_destroy();
    session_start();
    exit;
}

$_SESSION['last_activity'] = time();
