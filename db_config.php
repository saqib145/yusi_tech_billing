<?php
// db_config.php

// Database credentials
define('DB_SERVER', 'localhost'); // Your database server (e.g., 'localhost' or an IP)
define('DB_USERNAME', 'root');   // Your database username
define('DB_PASSWORD', '');       // Your database password
define('DB_NAME', 'crm_db');     // The database name we created

// Attempt to connect to MySQL database using PDO
try {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If connection fails, terminate the script and display the error
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
