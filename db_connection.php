<?php
// Database configuration
$host = 'localhost';        // Host name (change if needed)
$db = 'donation_db';        // Database name (replace with your DB name)
$user = 'root';             // MySQL username (change if needed)
$pass = '';                 // MySQL password (change if needed)

// Create a new PDO instance for the connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
} catch (PDOException $e) {
    // If there is an error, display it and stop the script
    die("Connection failed: " . $e->getMessage());
}
?>
