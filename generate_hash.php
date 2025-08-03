<?php
// generate_hash.php
// Yeh script aapke diye gaye password ka hash generate karega.
$password_to_hash = 'admin123';
$hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT);
echo "Your hashed password for 'admin123' is: <br>";
echo "<strong>" . htmlspecialchars($hashed_password) . "</strong>";
echo "<br><br>Please copy this string and paste it into your crm_db.sql file.";
?>