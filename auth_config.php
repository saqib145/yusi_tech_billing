<?php
// auth_config.php

// Start the session at the very beginning of the script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to redirect to the login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        // Redirect to login page
        header('Location: login.php');
        exit(); // Stop further script execution
    }
}

// Function to redirect to the dashboard if already logged in (e.g., from login page)
function redirectToDashboardIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit();
    }
}
?>
