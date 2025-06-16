<?php
// dbh.inc.php – Database connection script using PDO (Highly Secure)

// Step 1: DB Credentials - Update these with your actual database details
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_username';
$pass = 'your_password';

// Step 2: DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Step 3: PDO Options for security and error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Step 4: Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Step 5: Log error internally (never show raw error to users)
    error_log("DB Connection Error: " . $e->getMessage());

    // Optional: Redirect user to a friendly error page
    header("Location: /error.php?msg=" . urlencode("Database connection error. Please try again later."));
    exit;
}
