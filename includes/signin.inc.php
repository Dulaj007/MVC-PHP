<?php
// === File: includes/signin.inc.php ===

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../signin.php?error=invalidaccess");
    exit();
}

require_once 'config/config.php'; 
require_once 'config/dbh.inc.php';
require_once 'controllers/signin.contr.php'; 
require_once 'models/signin.model.php'; 
require_once 'models/profile.model.php'; 

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$ip = $_SERVER['REMOTE_ADDR'];
$input1 = isset($_POST['input1']) ? sanitize($_POST['input1']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

try {
    // Input empty check
    if (empty($input1) || empty($password)) {
        header("Location: ../signin.php?error=emptyinput");
        exit();
    }

    // Throttle check
    $throttleStatus = checkLoginThrottle($ip);
    if ($throttleStatus === "lockedout") {
        header("Location: ../signin.php?error=lockedout");
        exit();
    }


    $isEmail = checkIfEmail($input1);

    if ($isEmail) {
        $loginFail = verifyUserLogin($pdo, 'email', $input1, $password);
    } else {
        $loginFail = verifyUserLogin($pdo, 'username', $input1, $password);
    }

    if ($loginFail) {
        registerFailedLoginAttempt($ip);

        if ($throttleStatus === "toomanyattempts") {
            header("Location: ../signin.php?error=toomanyattempts");
        } else {
            header("Location: ../signin.php?error=invalidcredentials");
        }
        exit();
    }

     else {
            // Fetch full user data after successful login
        $userData = fetchUserData($pdo, $input1); // we'll build this

        $_SESSION['user'] = [
            'username' => $userData['username'],
            'firstName' => $userData['firstName'],
            'lastName' => $userData['lastName'],
            'email' => $userData['email'],
            'phoneNumber' => $userData['phoneNumber'],
            'accountType' => $userData['accountType']
        ];

        resetLoginAttempts($ip);
        header("Location: ../profile.php");
        exit();

    }

} catch (Exception $e) {
    error_log("[Login Error] " . $e->getMessage() . PHP_EOL, 3, __DIR__ . "/../includes/logs/signin_error.log");
    header("Location: ../signin.php?error=somethingwrong");
    exit();
}
