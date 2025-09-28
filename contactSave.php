<?php
session_start();
require_once 'configMysql.php'; // Make sure you have your database connection file

// Initialize variables
$message = '';
$message_type = 'error'; // Default to error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $userID = isset($_POST['userID']) && !empty($_POST['userID']) ? $_POST['userID'] : null;
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $messageText = trim($_POST['message']);
        
        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($subject) || empty($messageText)) {
            throw new Exception('All fields are required.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        // Prepare SQL statement - CORRECTED TO MATCH YOUR ACTUAL TABLE COLUMNS
        $sql = "INSERT INTO contact_form (userID, first_name, last_name, email, subject, message, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        // Bind parameters - userID can be NULL for guests
        // For NULL userID, we need to handle it differently
        if ($userID) {
            $stmt->bind_param("isssss", $userID, $firstName, $lastName, $email, $subject, $messageText);
        } else {
            // For guest users (NULL userID), we need to use a different approach
            $null = null;
            $stmt->bind_param("isssss", $null, $firstName, $lastName, $email, $subject, $messageText);
        }
        
        // Execute the statement
        if ($stmt->execute()) {
            $message = 'Thank you for your message! We will get back to you soon.';
            $message_type = 'success';
        } else {
            throw new Exception('Failed to save your message. Please try again. Error: ' . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $message = $e->getMessage();
        $message_type = 'error';
        // Log the error for debugging
        error_log("Contact form error: " . $e->getMessage());
    }
    
    // Store message in session to display on the contact page
    $_SESSION['contact_message'] = $message;
    $_SESSION['contact_message_type'] = $message_type;
    
    // Redirect back to contact page
    header('Location: contactUs.php');
    exit();
    
} else {
    // If not POST request, redirect to contact page
    header('Location: contactUs.php');
    exit();
}
?>