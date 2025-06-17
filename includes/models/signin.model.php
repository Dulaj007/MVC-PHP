<?php
define('PEPPER', 'your-pepper-string-here');
function verifyUserLogin(PDO $pdo, string $tableField, string $input1, string $password): bool {
    // ✅ Whitelist only valid columns
    $allowedFields = ['email', 'username'];
    if (!in_array($tableField, $allowedFields, true)) {
        throw new Exception("Invalid login field.");
    }

    // ✅ Create query using the exact field, manually embedded
    $sql = "SELECT * FROM users WHERE " . $tableField . " = :input LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':input', $input1, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return true; // User not found
    }

    // ✅ Check password using PEPPER
    $peppered = $password . PEPPER;
    if (!password_verify($peppered, $user['password'])) {
        return true; // Password incorrect
    }

    // ✅ You can also return user data here if needed
    return false; // Login successful

}
