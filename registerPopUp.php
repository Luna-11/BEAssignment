<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database configuration
require_once 'configMysql.php';

// Set content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if all required fields are present
    if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }
    
    // Get and sanitize input data
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Validate inputs
    if (empty($first_name)) {
        $response['message'] = "Please enter your first name.";
        echo json_encode($response);
        exit;
    }
    
    if (empty($last_name)) {
        $response['message'] = "Please enter your last name.";
        echo json_encode($response);
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Please enter a valid email address.";
        echo json_encode($response);
        exit;
    }
    
    if (strlen($password) < 6) {
        $response['message'] = "Password must be at least 6 characters long.";
        echo json_encode($response);
        exit;
    }
    
    try {
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE mail = ?";
        $check_stmt = $conn->prepare($check_sql); // Changed $mysqli to $conn
        
        if (!$check_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $check_stmt->bind_param("s", $email);
        
        if (!$check_stmt->execute()) {
            throw new Exception("Execute failed: " . $check_stmt->error);
        }
        
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $response['message'] = "This email is already registered.";
            echo json_encode($response);
            $check_stmt->close();
            exit;
        }
        $check_stmt->close();
        
        // Insert new user
        $insert_sql = "INSERT INTO users (first_name, last_name, mail, password) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql); // Changed $mysqli to $conn
        
        if (!$insert_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
        
        if ($insert_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registration successful! You can now login.";
        } else {
            throw new Exception("Execute failed: " . $insert_stmt->error);
        }
        
        $insert_stmt->close();
        
    } catch (Exception $e) {
        $response['message'] = "Database error: " . $e->getMessage();
    }
    
} else {
    $response['message'] = "Invalid request method.";
}

// Close connection
$conn->close(); // Changed $mysqli to $conn

// Return JSON response
echo json_encode($response);
?>