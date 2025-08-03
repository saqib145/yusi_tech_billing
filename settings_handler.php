<?php
// settings_handler.php

// Include the database configuration file
require_once 'db_config.php';

// Set content type to JSON for API responses
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Handle POST request (saving settings)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $companyName = filter_input(INPUT_POST, 'company_name', FILTER_SANITIZE_STRING);
    $companyEmail = filter_input(INPUT_POST, 'company_email', FILTER_SANITIZE_EMAIL);
    $companyPhone = filter_input(INPUT_POST, 'company_phone', FILTER_SANITIZE_STRING);
    $companyAddress = filter_input(INPUT_POST, 'company_address', FILTER_SANITIZE_STRING);
    $dateFormat = filter_input(INPUT_POST, 'date_format', FILTER_SANITIZE_STRING);
    $timeFormat = filter_input(INPUT_POST, 'time_format', FILTER_SANITIZE_STRING);
    $currency = filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($companyName) || empty($companyEmail)) {
        $response['message'] = 'Company name and email are required.';
        echo json_encode($response);
        exit();
    }
    if (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid company email format.';
        echo json_encode($response);
        exit();
    }

    $logoPath = null;
    // Handle logo upload
    if (isset($_FILES['logo_upload']) && $_FILES['logo_upload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded logos
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        $fileTmpPath = $_FILES['logo_upload']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['logo_upload']['name']); // Unique file name
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $logoPath = $destPath;
        } else {
            $response['message'] = 'Failed to upload logo.';
            echo json_encode($response);
            exit();
        }
    }

    try {
        // Fetch current settings to get existing logo path if no new logo is uploaded
        $stmt = $pdo->prepare("SELECT logo_path FROM company_settings WHERE id = 1");
        $stmt->execute();
        $currentSettings = $stmt->fetch();
        $existingLogoPath = $currentSettings['logo_path'] ?? null;

        // Use the new logo path if uploaded, otherwise keep the existing one
        $finalLogoPath = $logoPath ?? $existingLogoPath;

        // Prepare an UPDATE statement for the single settings row (id=1)
        // We assume there's always one row with id=1 for global settings
        $sql = "UPDATE company_settings SET
                    company_name = :company_name,
                    company_email = :company_email,
                    company_phone = :company_phone,
                    company_address = :company_address,
                    logo_path = :logo_path,
                    date_format = :date_format,
                    time_format = :time_format,
                    currency = :currency
                WHERE id = 1"; // Assuming a single settings entry with ID 1

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':company_email', $companyEmail);
        $stmt->bindParam(':company_phone', $companyPhone);
        $stmt->bindParam(':company_address', $companyAddress);
        $stmt->bindParam(':logo_path', $finalLogoPath);
        $stmt->bindParam(':date_format', $dateFormat);
        $stmt->bindParam(':time_format', $timeFormat);
        $stmt->bindParam(':currency', $currency);

        // Execute the statement
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Settings saved successfully!';
            // Return the new logo path if it was updated, so the frontend can update the preview
            if ($logoPath) {
                $response['new_logo_path'] = $logoPath;
            }
        } else {
            $response['message'] = 'Failed to save settings.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}
// Handle GET request (fetching settings)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Select all settings from the table
        $stmt = $pdo->prepare("SELECT * FROM company_settings WHERE id = 1"); // Fetch the single settings entry
        $stmt->execute();
        $settings = $stmt->fetch(); // Fetch as associative array

        if ($settings) {
            $response['success'] = true;
            $response['data'] = $settings;
        } else {
            $response['message'] = 'No settings found.';
            // Optionally, insert default settings if none exist
            // This case should ideally be handled by the initial SQL insert
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    // Handle unsupported request methods
    $response['message'] = 'Unsupported request method.';
}

// Encode the response array to JSON and output it
echo json_encode($response);
?>
