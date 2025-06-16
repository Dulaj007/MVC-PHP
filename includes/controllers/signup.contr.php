<?php

// Function to check if any of the required fields are empty
function emptyFields(array $inputs): bool {
    foreach ($inputs as $input) {
        if (empty($input)) {
            return true; // At least one field is empty
        }
    }
    return false; // All fields are filled
}
// Function to check some input fields length
function isInputLengthExceeded(array $inputs, int $maxLength = 15): bool {
    foreach ($inputs as $input) {
        if (strlen($input) > $maxLength) {
            return true; // length exceeded
        }
    }
    return false; // all inputs within limit
}


// Function to check if the input name has non-alphabet characters
function validateNames(array $inputs): bool {
    foreach ($inputs as $input){
        if (!preg_match("/^[a-zA-Z]+$/", $input)) {
            return true; // If any input has non-alphabet characters
        }
    }
    return false; // All inputs are valid
}


// Funtion to check email type
function isInvalidEmail(string $email): bool {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check password
function isPasswordValid(string $password): bool {
    if (strlen($password) < 8) {
        return true;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return true;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return true;
    }
    if (!preg_match('/[!@#\$%\^&\*]/', $password)) {
        return true;
    }
    return false;
}

//Function to check is the passwrord fileds are same
function isPasswordSame(string $password,string $confirmPassword): bool {
    if ($password == $confirmPassword){
        return false;
    }
    return true;
}

// Function to check if the user is under 18 years old
function isUnderage(string $dob): bool {
    $birthDate = new DateTime($dob);
    $today = new DateTime();

    $age = $today->diff($birthDate)->y;

    return $age < 18;
}

//Function to check phone number type and digits length
function isInvalidPhone(string $countryCode, string $phone): bool {
    // Check if phone contains only digits
    if (!ctype_digit($phone)) {
        return true;
    }

    // Set required length per country
    $requiredLength = match ($countryCode) {
        '+94' => 9,
        '+91', '+1', '+44' => 10,
        default => 10,
    };

    // Check if phone length matches
    return strlen($phone) !== $requiredLength;
}

//Function to check account type
function isInvalidAccountType(string $accountType): bool {
    $validTypes = ['A', 'B', 'C'];
    return !in_array($accountType, $validTypes, true);
}

//Function to check google reCaptcha
function isRecaptchaValid(string $recaptchaResponse): bool {
    $secretKey = '6Lc4X2IrAAAAAA_wtjLyrInYDPeu5DwiMoN1bXGp'; // Replace this with your actual secret key

    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null // Optional
    ];

    // Use cURL to send POST request to Google
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return false; // If cURL fails, treat as failed reCAPTCHA
    }

    $result = json_decode($response, true);

    return isset($result['success']) && $result['success'] === true;
}
