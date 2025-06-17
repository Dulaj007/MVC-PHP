<?php
function fetchUserData(PDO $pdo, string $input): array {
    $sql = "SELECT firstName, lastName, username, email, phoneNumber, accountType 
            FROM users 
            WHERE email = :email OR username = :username 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $input);
    $stmt->bindValue(':username', $input);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User data not found.");
    }

    return $user;
}

