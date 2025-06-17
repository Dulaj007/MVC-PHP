<?php
require_once 'includes/config/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: signin.php?error=unauthorized");
    exit();
}

$user = $_SESSION['user'];
$fullName = $user['firstName'] . ' ' . $user['lastName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <style>
        body { font-family: sans-serif; background: #f7f7f7; padding: 20px; }
        .profile-box { background: #fff; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .logout-btn { margin-top: 20px; display: block; text-align: center; }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>Hello, <?= htmlspecialchars($fullName) ?> 👋</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phoneNumber']) ?></p>
    <p><strong>Account Type:</strong> <?= htmlspecialchars($user['accountType']) ?></p>

    <a href="logout.php" class="logout-btn">🔓 Logout</a>
</div>

</body>
</html>
