<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS BMI_PHP_APP");
    $conn->exec("USE BMI_PHP_APP");

    // Create the AppUsers table
    $conn->exec("CREATE TABLE IF NOT EXISTS AppUsers (
        AppUserID INT AUTO_INCREMENT PRIMARY KEY,
        Username VARCHAR(50) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create the BMIUsers table
    $conn->exec("CREATE TABLE IF NOT EXISTS BMIUsers (
        BMIUserID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100) NOT NULL,
        Age INT,
        Gender ENUM('Male', 'Female', 'Other'),
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create the BMIRecords table
    $conn->exec("CREATE TABLE IF NOT EXISTS BMIRecords (
        RecordID INT AUTO_INCREMENT PRIMARY KEY,
        BMIUserID INT,
        Height FLOAT NOT NULL,
        Weight FLOAT NOT NULL,
        BMI FLOAT NOT NULL,
        RecordedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (BMIUserID) REFERENCES BMIUsers(BMIUserID) ON DELETE CASCADE
    )");

    // If everything was successful, show a success message
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Database Initialized',
                text: 'Database and tables initialized successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'index.php'; // Redirect after 2 seconds
            });
          </script>";
} catch(PDOException $e) {
    // If there's an error, show an error message
    $errorMessage = addslashes($e->getMessage()); // Escape for use in JS
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Connection Failed',
                text: '$errorMessage',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'index.php'; // Redirect after 3 seconds
            });
          </script>";
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
