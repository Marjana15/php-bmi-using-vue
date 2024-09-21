<?php
// register.php
require 'db.php'; // Ensure the database connection is properly set up

header('Content-Type: application/json'); // Set header to return JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirm = htmlspecialchars($_POST['password_confirmation']);

    // Check if passwords match
    if ($password !== $password_confirm) {
        $response['message'] = 'Passwords do not match.';
        echo json_encode($response);
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if username already exists
    try {
        $stmt = $conn->prepare("SELECT * FROM AppUsers WHERE Username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Username already exists
            $response['message'] = 'duplicate'; // Set message to 'duplicate' for Vue handling
        } else {
            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO AppUsers (Username, Password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // Registration successful
            $response['success'] = true;
            $response['message'] = 'Registration successful.';
        }
    } catch (PDOException $e) {
        // Handle any errors that occur
        error_log("Error during registration: " . $e->getMessage());
        $response['message'] = 'An error occurred during registration.';
    }
}

// Return JSON response for Vue.js to handle
echo json_encode($response);
?>
