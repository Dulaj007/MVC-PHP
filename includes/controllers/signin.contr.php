<?php

function checkIfEmail(string $input): bool {
    return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
}


// Get number of login attempts for this IP
function getLoginAttempts(string $ip): array {
    $file = __DIR__ . "/../logs/attempts_" . md5($ip) . ".log";
    if (!file_exists($file)) return [0, 0];
    [$count, $timestamp] = explode('|', file_get_contents($file));
    return [(int)$count, (int)$timestamp];
}

// Save login attempts
function saveLoginAttempts(string $ip, int $count, int $timestamp): void {
    $file = __DIR__ . "/../logs/attempts_" . md5($ip) . ".log";
    file_put_contents($file, "$count|$timestamp");
}

// Reset login attempts
function resetLoginAttempts(string $ip): void {
    $file = __DIR__ . "/../logs/attempts_" . md5($ip) . ".log";
    if (file_exists($file)) unlink($file);
}

// Main function to check login attempt status
function checkLoginThrottle(string $ip): string {
    list($attempts, $firstAttemptTime) = getLoginAttempts($ip);
    $now = time();

    // If locked out within 6 hours
    if ($attempts >= 6 && ($now - $firstAttemptTime) < 21600) {
        return "lockedout";
    }

    // If 6 hours have passed, reset
    if ($attempts >= 6 && ($now - $firstAttemptTime) >= 21600) {
        resetLoginAttempts($ip);
        return "clear";
    }

    // If reached 4th attempt
    if ($attempts === 4) {
        return "toomanyattempts";
       
    }

    return "clear";
}

// Call this when login fails
function registerFailedLoginAttempt(string $ip): void {
    list($attempts, $firstAttemptTime) = getLoginAttempts($ip);
    $now = time();

    $attempts++;
    if ($attempts === 1) $firstAttemptTime = $now;

    saveLoginAttempts($ip, $attempts, $firstAttemptTime);
}