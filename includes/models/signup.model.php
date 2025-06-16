<?php 
// Function to check if the input name is taken
function uniqueName(PDO $pdo, string $tableName, string $fieldName, string $input): bool {
    $sql = "SELECT 1 FROM $tableName WHERE $fieldName = :input LIMIT 1";

    // Use prepared statement with bound value
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':input', $input, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch() ? true : false; // true if record exists
}

//Function to 1++ refferal count
function incrementReferralCount(PDO $pdo, string $referral): void {
    // Step 1: Check if a user exists with the given referral code
    $stmt = $pdo->prepare("SELECT referralsCount FROM users WHERE referral = :referral");
    $stmt->execute(['referral' => $referral]);

    // Step 2: If referral found, increment the referralsCount
    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE users SET referralsCount = referralsCount + 1 WHERE referral = :referral");
        $update->execute(['referral' => $referral]);
    }
}

define('PEPPER', 'your-pepper-string-here');

function hashSensitiveData(string $data): string {
    $salt = bin2hex(random_bytes(16));  // 32 hex chars = 16 bytes salt
    $peppered = $data . PEPPER;
    $hashed = password_hash($peppered . $salt, PASSWORD_DEFAULT);
    if (!$hashed) {
        throw new Exception("Failed to hash sensitive data.");
    }
    // Store salt with hash separated by $
    return $hashed . '$' . $salt;
}

function hashPasswordSecurely(string $password): string {
    $peppered = $password . PEPPER;
    $hashed = password_hash($peppered, PASSWORD_DEFAULT);
    if (!$hashed) {
        throw new Exception("Failed to hash password securely.");
    }
    return $hashed;
}

function generateReferralCode(PDO $pdo): string {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = (int)$row['total'] + 1;
        return str_pad((string)$count, 7, "0", STR_PAD_LEFT);
    } catch (PDOException $e) {
        throw new Exception("Error generating referral code.");
    }
}

function formatPhoneNumber(string $countryCode, string $phone): string {
    // Remove non-numeric chars from phone
    $cleanPhone = preg_replace('/\D/', '', $phone);
    // Remove plus sign from country code if any
    $cleanCountry = ltrim($countryCode, '+');
    $fullPhone = '+' . $cleanCountry . $cleanPhone;

    // You can add length validation here or in validation step
    return $fullPhone;
}

function createUser(
    PDO $pdo,
    string $firstName,
    string $lastName,
    string $username,
    string $email,
    string $password,
    string $dob,
    string $referral,
    string $countryCode,
    string $phone,
    string $accountType
): void {
    try {
        $hashedFirstName = hashSensitiveData($firstName);
        $hashedLastName = hashSensitiveData($lastName);
        $hashedEmail = hashSensitiveData($email);
        $hashedPhone = hashSensitiveData($phone);
        $hashedDob = hashSensitiveData($dob);
        $hashedPassword = hashPasswordSecurely($password);
        $formattedPhone = formatPhoneNumber($countryCode, $phone);
        $referralCode = generateReferralCode($pdo);

        $stmt = $pdo->prepare("INSERT INTO users (
            first_name, last_name, username, email, password, dob, phone_number, account_type, referral_code, referralsCount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");

        $stmt->execute([
            $hashedFirstName,
            $hashedLastName,
            $username,
            $hashedEmail,
            $hashedPassword,
            $hashedDob,
            $formattedPhone,
            $accountType,
            $referralCode
        ]);
    } catch (Exception $e) {
        throw $e; // Rethrow to handle outside
    }
}
