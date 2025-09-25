<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include('configMysql.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contactFirstName'])) {
    
    // Sanitize input
    $firstName = trim(filter_var($_POST['contactFirstName'], FILTER_SANITIZE_STRING));
    $lastName = trim(filter_var($_POST['contactLastName'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL));
    $subject = trim(filter_var($_POST['contactSubject'], FILTER_SANITIZE_STRING));
    $message = trim(filter_var($_POST['contactMessage'], FILTER_SANITIZE_STRING));

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error_message'] = 'All fields are required';
        header('Location: contactUs.php');
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format';
        header('Location: contactUs.php');
        exit;
    }

    // Set userID (NULL if not logged in)
    $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;

    try {
        // Check if connection exists
        if (!$conn) {
            throw new Exception('Database connection failed');
        }

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_form(userID, FirstName, LastName, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        
        if ($stmt === false) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("isssss", $userID, $firstName, $lastName, $email, $subject, $message);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Thank you for your message! We will get back to you soon.';
        } else {
            throw new Exception('Failed to execute statement: ' . $stmt->error);
        }

        // Close statement
        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error_message'] = 'Sorry, there was an error sending your message. Please try again.';
    }

    // Redirect back to contact page
    header('Location: contactUs.php');
    exit;
} else {
    // If someone tries to access this page directly
    header('Location: contactUs.php');
    exit;
}
?>