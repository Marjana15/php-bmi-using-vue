<?php
// Start the session
session_start();

// Include the database connection
require 'php/db.php';

// Initialize the database (if needed)
require 'init_db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, display the login form
    require 'html/login_form.php';
} else {
    // If logged in, display the BMI calculator
    require 'html/header.php';
    require 'html/calculator_form.php';
    require 'html/footer.php';
}
?>
