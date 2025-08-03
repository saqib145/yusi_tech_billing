<?php
// client_handler.php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/auth_config.php';

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $company_name = filter_input(INPUT_POST, 'company_name', FILTER_SANITIZE_STRING);
    $client_email = filter_input(INPUT_POST, 'client_email', FILTER_SANITIZE_EMAIL);
    $client_phone = filter_input(INPUT_POST, 'client_phone', FILTER_SANITIZE_STRING);
    $client_address = filter_input(INPUT_POST, 'client_address', FILTER_SANITIZE_STRING);

    // Basic validation: Either (first_name & last_name) OR company_name is required
    if ((empty($first_name) && empty($last_name)) && empty($company_name)) {
        $response['message'] = 'Either First Name & Last Name or Company Name is required.';
        echo json_encode($response);
        exit();
    }

    // Validate email format if provided
    if (!empty($client_email) && !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    // Combine first and last name for client_name if company_name is empty
    $client_name = '';
    if (!empty($company_name)) {
        $client_name = $company_name;
    } else {
        $client_name = trim($first_name . ' ' . $last_name);
    }

    try {
        // Prepare an INSERT statement
        $stmt = $pdo->prepare("INSERT INTO clients (client_name, client_email, client_phone, client_address) VALUES (:client_name, :client_email, :client_phone, :client_address)");

        // Bind parameters
        $stmt->bindParam(':client_name', $client_name);
        $stmt->bindParam(':client_email', $client_email);
        $stmt->bindParam(':client_phone', $client_phone);
        $stmt->bindParam(':client_address', $client_address);

        // Execute the statement
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Client added successfully!';
        } else {
            $response['message'] = 'Failed to add client. Please try again.';
        }
    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Client add error: " . $e->getMessage());
        $response['message'] = 'An error occurred while adding the client. Please try again later.';
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit();
?>
