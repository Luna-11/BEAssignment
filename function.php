<?php
function getEvent() {
    global $conn;
    
    $sql = "SELECT e.*, u.first_name, u.last_name 
            FROM event e 
            LEFT JOIN users u ON e.userID = u.id 
            ORDER BY e.eventDate ASC";
    
    $result = $conn->query($sql);
    $events = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

// Additional utility functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>