<?php
// login.php
require 'db.php';
header('Content-Type: application/json'); // Set the content type to JSON

$response = ['success' => false, 'message' => '']; // Initialize the response array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM AppUsers WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['Password'])) {
        session_start();
        $_SESSION['user_id'] = $user['AppUserID'];
        
        // Set the response for a successful login
        $response['success'] = true;
        $response['message'] = 'Login successful.';
    } else {
        // Invalid credentials
        $response['message'] = 'Invalid username or password.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Return the JSON response to the front-end
echo json_encode($response);
?>
