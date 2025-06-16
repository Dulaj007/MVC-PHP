<?php

// Redirect if the form was not submitted
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../signup.php?error=invalidaccess");
    exit();
}

// Include required files
require_once '../config/dbh.inc.php'; // DB connection
require_once 'signup.contr.php';
require_once '../models/signup.model.php';

// Helper function to sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Sanitize user input
$firstName = isset($_POST['first_name']) ? sanitize($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? sanitize($_POST['last_name']) : '';
$username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$dob = isset($_POST['dob']) ? sanitize($_POST['dob']) : '';
$referral = isset($_POST['referral']) ? sanitize($_POST['referral']) : '';
$countryCode = isset($_POST['country_code']) ? sanitize($_POST['country_code']) : '';
$phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
$accountType = isset($_POST['account_type']) ? sanitize($_POST['account_type']) : '';
$recaptchaResponse = isset($_POST['g-recaptcha-response']) ;
try {
     // Validate input using controller functions
    if (emptyFields([$firstName, $lastName, $username, $email, $password, $confirmPassword, $dob, $phone, $accountType])) {
        header("Location: ../../signup.php?error=emptyfields");
        exit();
    }

    if (isInputLengthExceeded([$firstName, $lastName, $username])) {
        // Handle error: input too long
    }

    if (validateNames([$firstName, $lastName])){
        header("Location: ../../signup.php?error=emptyfields");
        exit();
    }

    if (uniqueName($pdo, 'users', 'username', $username)) {
    header("Location: ../../signup.php?error=usernametaken");
    exit();
    }

    if (uniqueName($pdo, 'users', 'email', $email)) { 
    header("Location: ../../signup.php?error=emailtaken");
    exit();
    }

    if (isInvalidEmail($emailInput)) {
    // Handle error: invalid email
    }

    if (isPasswordValid($password)) {
    // Password is invalid — show error or handle it
    }

    if (isPasswordSame($password,$confirmPassword)){

    }

    if (isUnderage($dob)) {
    // Show error: User must be at least 18 years old
    }

    if (incrementReferralCount($pdo, $referralCode)){

    }

    if (isInvalidPhone($countryCode, $phone)) {
        // Handle invalid phone (e.g., return error or redirect)
    }
    if (isInvalidAccountType($accountType)) {
    // Handle invalid account type (e.g., show error or redirect)
    }



if (!isRecaptchaValid($recaptchaResponse)) {
    header("Location: ../../signup.php?error=recaptcha_failed");
    exit();
}
   
try {
    createUser(
        $pdo,
        $firstName,
        $lastName,
        $username,
        $email,
        $password,
        $dob,
        $referral,
        $countryCode,
        $phone,
        $accountType
    );

    // If successful, redirect with success message
    header("Location: ../../signup.php?success=accountcreated");
    exit();
    } catch (Exception $e) {
    // On error, redirect back with error message
    $error = urlencode($e->getMessage());
    header("Location: ../../signup.php?error=$error");
    exit();
}

} catch (PDOException $e) {
    header("Location: ../../signup.php?error=stmtfailed");
    exit();
}