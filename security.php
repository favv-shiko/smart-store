<?php

// 1. Input Validation
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// 2. Protect against SQL Injection
function executeQuery($conn, $query, $params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param(...$params);
    $stmt->execute();
    return $stmt;
}

// 3. Secure Session Handling
session_start([
    'cookie_lifetime' => 86400,
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
]);

// 4. XSS Protection
function escapeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// 5. Secure File Upload
function secureFileUpload($file, $allowedTypes, $uploadDir) {
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileType, $allowedTypes)) {
        return 'Invalid file type.';
    }
    if ($fileSize > 5000000) { // Limit to 5MB
        return 'File is too large.';
    }

    $newFileName = uniqid() . '.' . $fileExt;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
        return 'File uploaded successfully.';
    } else {
        return 'Failed to upload file.';
    }
}

// 6. Force HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

// 7. Access Control
function checkAccess($roleRequired) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $roleRequired) {
        header("HTTP/1.1 403 Forbidden");
        exit('Access denied.');
    }
}

// 8. Log Suspicious Activities
function logActivity($message) {
    $logFile = 'activity.log';
    $logMessage = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// 9. Limit Login Attempts
function limitLoginAttempts($ip) {
    $maxAttempts = 5;
    $timeout = 15 * 60; // 15 minutes
    $attemptsFile = 'login_attempts.json';

    $data = file_exists($attemptsFile) ? json_decode(file_get_contents($attemptsFile), true) : [];

    if (isset($data[$ip]) && $data[$ip]['count'] >= $maxAttempts && time() - $data[$ip]['last'] < $timeout) {
        exit('Too many login attempts. Try again later.');
    }

    if (!isset($data[$ip])) {
        $data[$ip] = ['count' => 0, 'last' => time()];
    }

    $data[$ip]['count']++;
    $data[$ip]['last'] = time();

    file_put_contents($attemptsFile, json_encode($data));
}

// 10. File Permission Check
function setFilePermissions($file, $permissions = 0644) {
    chmod($file, $permissions);
}

// 11. CSRF Protection
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Example Usage for CSRF Token
$csrfToken = generateCsrfToken();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        exit('Invalid CSRF token.');
    }
}

?>