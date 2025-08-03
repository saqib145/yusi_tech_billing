<?php
// login_handler.php

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
require_once 'db_config.php';

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Get password directly, don't sanitize before hashing check

    // Basic validation
    if (empty($email) || empty($password)) {
        $response['message'] = 'Please enter both email and password.';
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    try {
        // Prepare a SELECT statement to retrieve the user by email
        $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user row
        $user = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user && password_verify($password, $user['password_hash'])) {
            // Password is correct, start a new session
            session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['loggedin'] = true;

            $response['success'] = true;
            $response['message'] = 'Login successful!';
            $response['redirect'] = 'dashboard.php'; // Redirect to dashboard on success
        } else {
            // Invalid credentials
            $response['message'] = 'Invalid email or password.';
        }
    } catch (PDOException $e) {
        // Database error
        error_log("Login error: " . $e->getMessage()); // Log the error for debugging
        $response['message'] = 'An error occurred during login. Please try again later.';
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Return JSON response
echo json_encode($response);
?>
