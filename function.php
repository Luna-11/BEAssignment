<?php
// functions.php
include('./configMysql.php');

function showUser($userID) {
    global $conn;
    
    // Check if connection exists
    if (!$conn) {
        error_log("Database connection failed in showUser");
        return [];
    }
    
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $userData;
    } catch (Exception $e) {
        error_log("Error in showUser: " . $e->getMessage());
        return [];
    }
}

function getEvents($conn) {
    $events = array();
    
    $sql = "SELECT * FROM event ORDER BY eventDate ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    
    return $events;
}

function saveContactMessage($data) {
    global $conn;
    
    if (!$conn) {
        error_log("Database connection failed in saveContactMessage");
        return false;
    }
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO contact_form (userID, FirstName, LastName, email, subject, message, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param(
            "isssss",
            $data['userID'],
            $data['FirstName'],
            $data['LastName'],
            $data['email'],
            $data['subject'],
            $data['message']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    } catch (Exception $e) {
        error_log("Error in saveContactMessage: " . $e->getMessage());
        return false;
    }
}
?>