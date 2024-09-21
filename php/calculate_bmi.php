<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

$response = ['success' => false, 'message' => '', 'health_status' => '', 'tip' => ''];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Please log in to use the calculator.";
    echo json_encode($response);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];
    $gender = htmlspecialchars($_POST['gender']);
    $height = (float)$_POST['height'];
    $weight = (float)$_POST['weight'];

    // Validate height and weight
    if ($height <= 0 || $weight <= 0) {
        $response['message'] = "Height and weight must be positive numbers.";
        echo json_encode($response);
        exit;
    }

    // Calculate BMI
    $bmi = $weight / ($height * $height);

    try {
        // Insert user into BMIUsers table
        $stmt = $conn->prepare("INSERT INTO BMIUsers (Name, Age, Gender) VALUES (:name, :age, :gender)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        $bmiUserId = $conn->lastInsertId();

        // Insert BMI record into BMIRecords table
        $stmt = $conn->prepare("INSERT INTO BMIRecords (BMIUserID, Height, Weight, BMI) VALUES (:bmiUserId, :height, :weight, :bmi)");
        $stmt->bindParam(':bmiUserId', $bmiUserId);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':bmi', $bmi);
        $stmt->execute();

        // Determine health status and tips based on BMI
        if ($bmi < 18.5) {
            $health_status = "Underweight";
            $tip = "You are underweight. Consider eating more frequently and adding more healthy calories to your diet.";
        } elseif ($bmi >= 18.5 && $bmi < 24.9) {
            $health_status = "Normal weight";
            $tip = "You have a normal body weight. Great job! Maintain your healthy lifestyle.";
        } elseif ($bmi >= 25 && $bmi < 29.9) {
            $health_status = "Overweight";
            $tip = "You are slightly overweight. Consider regular exercise and a balanced diet.";
        } elseif ($bmi >= 30 && $bmi < 34.9) {
            $health_status = "Obesity (Class 1)";
            $tip = "You are in the obesity range. It is important to adopt a healthier diet and increase physical activity.";
        } elseif ($bmi >= 35 && $bmi < 39.9) {
            $health_status = "Obesity (Class 2)";
            $tip = "You are in a higher obesity range. Consult with a healthcare provider for a comprehensive weight-loss plan.";
        } else {
            $health_status = "Severe Obesity (Class 3)";
            $tip = "You are in a severe obesity range. Immediate lifestyle changes and medical attention are recommended.";
        }

        // Prepare the success response
        $response['success'] = true;
        $response['message'] = "Your BMI is " . number_format($bmi, 2);
        $response['health_status'] = $health_status;
        $response['tip'] = $tip;

    } catch (PDOException $e) {
        $response['message'] = "Database error: " . $e->getMessage();
    }
}

// Return the JSON response
echo json_encode($response);
?>
