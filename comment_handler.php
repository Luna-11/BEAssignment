<?php
session_start();
include('./configMysql.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$userID = $_SESSION['userID'];

// Handle adding new comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_comment') {
    $response = ['success' => false, 'error' => ''];
    
    $comment = trim($_POST['comment'] ?? '');
    $communityID = intval($_POST['communityID'] ?? 0);
    
    // Validate input
    if (empty($comment)) {
        $response['error'] = "Comment cannot be empty";
    } elseif (strlen($comment) > 300) {
        $response['error'] = "Comment must be less than 300 characters";
    } elseif ($communityID <= 0) {
        $response['error'] = "Invalid post";
    } else {
        // Insert comment into database
        $stmt = $conn->prepare("INSERT INTO comment (comment, userID, communityID, commentDate) VALUES (?, ?, ?, NOW())");
        
        if (!$stmt) {
            $response['error'] = "Database Error: " . $conn->error;
        } else {
            $stmt->bind_param("sii", $comment, $userID, $communityID);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['commentID'] = $stmt->insert_id;
            } else {
                $response['error'] = "Failed to add comment: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    
    echo json_encode($response);
    exit();
}

// Handle fetching comments
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'get_comments') {
    $communityID = intval($_GET['communityID'] ?? 0);
    
    if ($communityID <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
        exit();
    }
    
    try {
        $query = "
            SELECT c.*, u.first_name 
            FROM comment c 
            LEFT JOIN users u ON c.userID = u.id 
            WHERE c.communityID = ? 
            ORDER BY c.commentDate ASC
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $communityID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $username = isset($row['first_name']) && !empty($row['first_name']) ? $row['first_name'] : 'User';
            
            $comments[] = [
                'commentID' => $row['commentID'],
                'comment' => htmlspecialchars($row['comment']),
                'username' => htmlspecialchars($username),
                'commentDate' => $row['commentDate'],
                'formattedDate' => date('F j, Y g:i A', strtotime($row['commentDate']))
            ];
        }
        
        echo json_encode(['success' => true, 'comments' => $comments]);
        $stmt->close();
        
    } catch (Exception $e) {
        // If the above fails, just get comments without user info
        $simpleQuery = "SELECT * FROM comment WHERE communityID = ? ORDER BY commentDate ASC";
        $stmt = $conn->prepare($simpleQuery);
        $stmt->bind_param("i", $communityID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = [
                'commentID' => $row['commentID'],
                'comment' => htmlspecialchars($row['comment']),
                'username' => 'User ' . $row['userID'],
                'commentDate' => $row['commentDate'],
                'formattedDate' => date('F j, Y g:i A', strtotime($row['commentDate']))
            ];
        }
        
        echo json_encode(['success' => true, 'comments' => $comments]);
        $stmt->close();
    }
    exit();
}

// Handle fetching comment count
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'get_comment_count') {
    $communityID = intval($_GET['communityID'] ?? 0);
    
    if ($communityID <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
        exit();
    }
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM comment WHERE communityID = ?");
    $stmt->bind_param("i", $communityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    echo json_encode(['success' => true, 'count' => $count]);
    $stmt->close();
    exit();
}
?>