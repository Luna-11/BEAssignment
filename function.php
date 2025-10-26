<?php
// functions.php
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

function getDifficultyLevels($conn) {   
    $levels = [];
    if (!$conn) {
        error_log("Database connection failed in getDifficultyLevels");
        return [];
    }
    
    try {
        // Use the correct column names from your database
        $sql = "SELECT difficultyID, difficultyName FROM difficultyLev ORDER BY difficultyID";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return [];
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $levels[] = $row;
            }
        } else {
            error_log("No rows found in difficultyLev table");
        }
        
        $stmt->close();
        return $levels;
    } catch (Exception $e) {
        error_log("Error in getDifficultyLevels: " . $e->getMessage());
        return [];
    }
}


function getFoodType($conn) {
    $sql = "SELECT foodTypeID, foodType FROM foodType";
    $result = $conn->query($sql);
    $foodTypes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $foodTypes[] = $row;
        }
    }
    return $foodTypes;
}

function getDietPref($conn) {
    $sql = "SELECT dietID, dietName FROM dietPreferences";
    $result = $conn->query($sql);
    $dietPreferences = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dietPreferences[] = $row;
        }
    }
    return $dietPreferences;
}

function getCuisineType($conn) {
    if (!$conn) {
        error_log("Database connection failed in getCuisineType");
        return [];
    }
    
    try {
        // FIX: Select both ID and name
        $sql = "SELECT cuisineTypeID, cuisineType FROM cuisineType ORDER BY cuisineTypeID";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return [];
        }
        
        $stmt->execute();
        
        $cuisineTypes = [];
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            // Return both ID and name as an array
            $cuisineTypes[] = $row;
        }
        
        $stmt->close();
        return $cuisineTypes;
    } catch (Exception $e) {
        error_log("Error in getCuisineType: " . $e->getMessage());
        return [];
    }
}



function addRecipe($conn, $data) {
    try {
        $conn->begin_transaction();

        // DEBUG: Log the data being received
        error_log("Recipe data received in addRecipe: " . print_r($data, true));

        // CORRECTED SQL - matches your actual database structure
        $sql = "INSERT INTO recipe 
            (recipeName, difficultID, userID, image, recipeDescription, date, 
             cuisineTypeID, foodTypeID, dietaryID, ingredient) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
        
        error_log("SQL: " . $sql);
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // DEBUG each parameter
        error_log("Parameters for binding:");
        error_log("1. recipeName: " . substr($data['recipeName'] ?? 'NULL', 0, 50));
        error_log("2. difficultID: " . ($data['difficultID'] ?? 'NULL'));
        error_log("3. userID: " . ($data['userID'] ?? 'NULL'));
        error_log("4. image: " . ($data['image'] ?? 'NULL'));
        error_log("5. recipeDescription length: " . strlen($data['recipeDescription'] ?? ''));
        error_log("6. cuisineTypeID: " . ($data['cuisineTypeID'] ?? 'NULL'));
        error_log("7. foodTypeID: " . ($data['foodTypeID'] ?? 'NULL'));
        error_log("8. dietaryID: " . ($data['dietaryID'] ?? 'NULL'));
        error_log("9. ingredient length: " . strlen($data['ingredient'] ?? ''));

        // CORRECTED: 9 parameters for 9 placeholders (NOW() is not a parameter)
        $stmt->bind_param("siissiiis", 
            $data['recipeName'],        // string
            $data['difficultID'],       // int
            $data['userID'],            // int
            $data['image'],             // string
            $data['recipeDescription'], // string → goes to recipeDescription column
            $data['cuisineTypeID'],     // int
            $data['foodTypeID'],        // int
            $data['dietaryID'],         // int
            $data['ingredient']         // string
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert recipe: " . $stmt->error);
        }

        $recipeId = $conn->insert_id;
        $stmt->close();
        $conn->commit();

        error_log("Recipe inserted successfully with ID: " . $recipeId);
        return ['success' => true, 'recipeId' => $recipeId];
        
    } catch (Exception $e) {
        if ($conn) {
            $conn->rollback();
        }
        error_log("Error adding recipe: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to add recipe: ' . $e->getMessage()];
    }
}

// Add this function to your functions.php
function handleImageUpload($file) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        error_log("Image upload: No file or upload error");
        return null;
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        error_log("Invalid file type: " . $mimeType);
        return null;
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        error_log("File too large: " . $file['size']);
        return null;
    }
    
    // Create uploads directory if it doesn't exist
    $uploadDir = 'uploads/recipes/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'recipe_' . time() . '_' . uniqid() . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        error_log("Image uploaded successfully: " . $filePath);
        return $filePath;
    } else {
        error_log("Failed to move uploaded file");
        return null;
    }
}


function storeComment($conn, $userID, $comment, $communityID, $recipeID = null) {
    // Validate inputs
    if (empty($comment) || empty($communityID)) {
        return ["success" => false, "message" => "Comment and community ID are required"];
    }
    
    if (strlen($comment) > 300) {
        return ["success" => false, "message" => "Comment must be less than 300 characters"];
    }
    
    // Check if user exists and is logged in
    if (!$userID) {
        return ["success" => false, "message" => "User must be logged in to comment"];
    }
    
    // Check if community post exists
    $postCheck = $conn->prepare("SELECT postID FROM community WHERE postID = ?");
    $postCheck->bind_param("i", $communityID);
    $postCheck->execute();
    $postResult = $postCheck->get_result();
    
    if ($postResult->num_rows == 0) {
        return ["success" => false, "message" => "Community post not found"];
    }
    $postCheck->close();
    
    // Prepare and execute insert statement
    $date = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO comment (comment, userID, communityID, recipeID, commentDate) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        return ["success" => false, "message" => "Database error: " . $conn->error];
    }
    
    $stmt->bind_param("siiis", $comment, $userID, $communityID, $recipeID, $date);
    
    if ($stmt->execute()) {
        $commentID = $stmt->insert_id;
        $stmt->close();
        return ["success" => true, "message" => "Comment added successfully", "commentID" => $commentID];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ["success" => false, "message" => "Failed to add comment: " . $error];
    }
}


/**
 * Function to get comments for a community post
 */
function getComments($conn, $communityID) {
    $comments = [];
    
    // Check what columns exist in the users table
    $userColumns = $conn->query("SHOW COLUMNS FROM users");
    $userColumnNames = [];
    if ($userColumns) {
        while ($col = $userColumns->fetch_assoc()) {
            $userColumnNames[] = $col['Field'];
        }
    }
    
    // Build the query based on available columns
    $selectColumns = "c.*";
    $joinClause = "";
    
    if (in_array('username', $userColumnNames)) {
        $selectColumns .= ", u.username";
        $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
    } elseif (in_array('name', $userColumnNames)) {
        $selectColumns .= ", u.name as username";
        $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
    } elseif (in_array('email', $userColumnNames)) {
        $selectColumns .= ", u.email as username";
        $joinClause = "LEFT JOIN users u ON c.userID = u.userID";
    } else {
        $selectColumns .= ", c.userID";
    }
    
    $stmt = $conn->prepare("
        SELECT $selectColumns 
        FROM comment c 
        $joinClause 
        WHERE c.communityID = ? 
        ORDER BY c.commentDate ASC
    ");
    
    if ($stmt) {
        $stmt->bind_param("i", $communityID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        $stmt->close();
    }
    
    return $comments;
}
// Helper function to detect the correct user ID column name
function detectUserIdColumn($conn) {
    // Since we know your table uses 'id', we can be more specific
    $possibleColumns = ['id', 'userID', 'user_id', 'userId', 'userid'];
    
    $result = $conn->query("SHOW COLUMNS FROM users");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (in_array($row['Field'], $possibleColumns)) {
                return $row['Field'];
            }
        }
    }
    
    // Default to 'id' since that's what your table uses
    return 'id';
}

// Update the getUserProfileMySQLi function to be more robust
function getUserProfileMySQLi($userID, $conn) {
    try {
        $idColumn = detectUserIdColumn($conn);
        $availableColumns = getTableColumns($conn, 'users');
        
        if (empty($availableColumns)) {
            return [
                'success' => false,
                'error' => 'Could not read users table structure'
            ];
        }
        
        // Build select columns - simplified since we know your table structure
        $selectColumns = [
            "$idColumn as userID",
            'first_name',
            'last_name', 
            'mail',
            'profileImage'
        ];
        
        $sql = "SELECT " . implode(', ', $selectColumns) . " FROM users WHERE $idColumn = ?";
        error_log("Executing SQL: " . $sql);
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user) {
            return [
                'success' => true,
                'user' => $user
            ];
        } else {
            return [
                'success' => false,
                'error' => 'User not found'
            ];
        }
    } catch (Exception $e) {
        error_log("Error in getUserProfileMySQLi: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Function to update user profile
function updateUserProfileMySQLi($userID, $data, $conn) {
    try {
        $idColumn = detectUserIdColumn($conn);
        $availableColumns = getTableColumns($conn, 'users');
        
        $updateFields = [];
        $params = [];
        $types = '';
        
        // Handle first name (check for different column names)
        if (isset($data['first_name'])) {
            if (in_array('first_name', $availableColumns)) {
                $updateFields[] = "first_name = ?";
                $params[] = trim($data['first_name']);
                $types .= 's';
            } elseif (in_array('firstName', $availableColumns)) {
                $updateFields[] = "firstName = ?";
                $params[] = trim($data['first_name']);
                $types .= 's';
            }
        }
        
        // Handle last name
        if (isset($data['last_name'])) {
            if (in_array('last_name', $availableColumns)) {
                $updateFields[] = "last_name = ?";
                $params[] = trim($data['last_name']);
                $types .= 's';
            } elseif (in_array('lastName', $availableColumns)) {
                $updateFields[] = "lastName = ?";
                $params[] = trim($data['last_name']);
                $types .= 's';
            }
        }
        
        // Handle email
        if (isset($data['mail'])) {
            if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'error' => 'Invalid email address'];
            }
            
            if (in_array('mail', $availableColumns)) {
                $updateFields[] = "mail = ?";
                $params[] = trim($data['mail']);
                $types .= 's';
            } elseif (in_array('email', $availableColumns)) {
                $updateFields[] = "email = ?";
                $params[] = trim($data['mail']);
                $types .= 's';
            }
        }
        
        if (empty($updateFields)) {
            return ['success' => false, 'error' => 'No fields to update or column names not found'];
        }
        
        $params[] = $userID;
        $types .= 'i';
        
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE $idColumn = ?";
        error_log("Update SQL: " . $sql);
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Profile updated successfully'
            ];
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error in updateUserProfileMySQLi: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Function to handle profile image upload
function handleProfileImageUpload($file, $userID) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File too large. Maximum size is 5MB.'];
    }
    
    // Create uploads directory if it doesn't exist
    $uploadDir = 'uploads/profiles/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'profile_' . $userID . '_' . time() . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return [
            'success' => true,
            'filePath' => $filePath,
            'message' => 'Profile image uploaded successfully'
        ];
    } else {
        return ['success' => false, 'error' => 'Failed to upload image'];
    }
}



//function to save recipes 

function isRecipeSaved($userID, $recipeID, $conn) {
    $sql = "SELECT id FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userID, $recipeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $isSaved = $result->num_rows > 0;
    $stmt->close();
    return $isSaved;
}

function getSavedRecipes($userID, $conn) {
    $savedQuery = "
        SELECT 
            r.recipeID,
            r.recipeName,
            r.image,
            r.recipeDescription,
            r.date,
            d.difficultyName,
            c.cuisineType,
            u.first_name,
            u.last_name,
            sr.saved_at
        FROM saved_recipes sr
        JOIN recipe r ON sr.recipe_id = r.recipeID
        LEFT JOIN difficultyLev d ON r.difficultID = d.difficultyID
        LEFT JOIN cuisineType c ON r.cuisineTypeID = c.cuisineTypeID
        LEFT JOIN users u ON r.userID = u.id
        WHERE sr.user_id = ?
        ORDER BY sr.saved_at DESC
    ";
    
    $stmt = $conn->prepare($savedQuery);
    $savedRecipes = [];
    
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $savedRecipes[] = $row;
            }
        }
        $stmt->close();
    }
    
    return $savedRecipes;
}


?>