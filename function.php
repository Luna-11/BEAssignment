<?php
// functions.php

function showUser($userID, $conn) {
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

function saveContactMessage($data, $conn) {
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

// ADD THIS MISSING FUNCTION
function getDietPref($conn) {
    if (!$conn) {
        error_log("Database connection failed in getDietPref");
        return [];
    }
    
    try {
        $sql = "SELECT dietName FROM dietPreferences ORDER BY dietID";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return [];
        }
        
        $stmt->execute();
        
        $dietPreferences = [];
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $dietPreferences[] = $row['dietName']; // Changed from dietaryName to dietName
        }
        
        $stmt->close();
        return $dietPreferences;
    } catch (Exception $e) {
        error_log("Error in getDietPref: " . $e->getMessage());
        return [];
    }
}

function getCuisineType($conn) {
    if (!$conn) {
        error_log("Database connection failed in getCuisineType");
        return [];
    }
    
    try {
        $sql = "SELECT cuisineType FROM cuisineType ORDER BY cuisineTypeID";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return [];
        }
        
        $stmt->execute();
        
        $cuisineTypes = [];
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $cuisineTypes[] = $row['cuisineType'];
        }
        
        $stmt->close();
        return $cuisineTypes;
    } catch (Exception $e) {
        error_log("Error in getCuisineType: " . $e->getMessage());
        return [];
    }
}

function getFoodType($conn) {
    $foodTypes = array();
    $sql = "SELECT foodType FROM foodType ORDER BY foodType";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $foodTypes[] = $row['foodType'];
        }
    }
    return $foodTypes;
}

function addRecipe($conn, $data) {
    try {
        $conn->begin_transaction();

        // DEBUG: Log the data being received
        error_log("Recipe data received: " . print_r($data, true));

        // UPDATED: Corrected the SQL to match your actual columns
        $stmt = $conn->prepare("INSERT INTO recipe 
            (recipeName, difficultID, userID, image, text, recipeDescription, date, 
             cuisineTypeID, foodTypeID, dietaryID, ingredient) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Handle NULLs/defaults - CORRECTED based on your form fields
        $image         = $data['image'] ?? null;
        $cuisineTypeID = $data['cuisineTypeID'] ?? null;
        $foodTypeID    = $data['foodTypeID'] ?? null;
        $dietaryID     = $data['dietaryID'] ?? null;
        
        // CORRECTED: Map form fields to correct database columns
        $instructions  = $data['recipeDescription'] ?? ''; // This goes to `text` column (instructions)
        $subtitle      = 'Delicious homemade recipe'; // Default value for recipeDescription column

        // DEBUG: Log the values before binding
        error_log("Binding values: 
            recipeName: {$data['recipeName']}
            difficultID: {$data['difficultID']}
            userID: {$data['userID']}
            image: $image
            instructions: " . substr($instructions, 0, 50) . "...
            subtitle: $subtitle
            cuisineTypeID: $cuisineTypeID
            foodTypeID: $foodTypeID
            dietaryID: $dietaryID
            ingredient: " . substr($data['ingredient'] ?? '', 0, 50) . "..."
        );

        // CORRECTED FIX: Count the parameters properly - we have 10 variables for 11 placeholders?
        // Let's check what we actually need to bind
        $bindParams = [
            $data['recipeName'],        // string
            $data['difficultID'],       // int  
            $data['userID'],            // int
            $image,                     // string
            $instructions,              // string → text column (instructions)
            $subtitle,                  // string → recipeDescription column (short description)
            $cuisineTypeID,             // int
            $foodTypeID,                // int
            $dietaryID,                 // int
            $data['ingredient']         // string
        ];

        // DEBUG: Count the parameters
        error_log("Number of parameters to bind: " . count($bindParams));
        error_log("Parameter types needed: siisssiiis (10 characters for 10 parameters)");

        // FIXED: Correct type definition string - we have 10 parameters, not 11
        $stmt->bind_param("siisssiiis", ...$bindParams);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert recipe: " . $stmt->error);
        }

        $recipeId = $conn->insert_id;
        $stmt->close();
        $conn->commit();

        return ['success' => true, 'recipeId' => $recipeId];
        
    } catch (Exception $e) {
        if ($conn) {
            $conn->rollback();
        }
        error_log("Error adding recipe: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to add recipe: ' . $e->getMessage()];
    }
}


function getCuisineTypeID($conn, $cuisineName) {
    if (empty($cuisineName)) return null;
    
    try {
        $stmt = $conn->prepare("SELECT cuisineTypeID FROM cuisineType WHERE cuisineType = ?");
        if (!$stmt) {
            error_log("Prepare failed in getCuisineTypeID: " . $conn->error);
            return null;
        }
        
        $stmt->bind_param("s", $cuisineName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row ? $row['cuisineTypeID'] : null;
    } catch (Exception $e) {
        error_log("Error in getCuisineTypeID: " . $e->getMessage());
        return null;
    }
}

function getFoodTypeID($conn, $foodTypeName) {
    if (empty($foodTypeName)) return null;
    
    try {
        $stmt = $conn->prepare("SELECT foodTypeID FROM foodType WHERE foodType = ?");
        if (!$stmt) {
            error_log("Prepare failed in getFoodTypeID: " . $conn->error);
            return null;
        }
        
        $stmt->bind_param("s", $foodTypeName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row ? $row['foodTypeID'] : null;
    } catch (Exception $e) {
        error_log("Error in getFoodTypeID: " . $e->getMessage());
        return null;
    }
}

function getDietPrefID($conn, $dietName) {
    if (empty($dietName)) return null;
    
    try {
        // FIXED: Corrected column name from dietaryID to dietID
        $stmt = $conn->prepare("SELECT dietID FROM dietPreferences WHERE dietName = ?");
        if (!$stmt) {
            error_log("Prepare failed in getDietPrefID: " . $conn->error);
            return null;
        }
        
        $stmt->bind_param("s", $dietName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        // FIXED: Changed from dietaryID to dietID
        return $row ? $row['dietID'] : null;
    } catch (Exception $e) {
        error_log("Error in getDietPrefID: " . $e->getMessage());
        return null;
    }
}

// CORRECT THE uploadImage FUNCTION (only keep this one, remove the duplicate):
function uploadImage($file) {
    $uploadDir = 'uploads/recipe/'; // ADDED trailing slash
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        error_log("Invalid file type: " . $file['type']);
        return null;
    }
    
    // Validate file size (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        error_log("File too large: " . $file['size']);
        return null;
    }
    
    // Generate unique filename
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $targetPath;
    }
    
    error_log("Failed to move uploaded file");
    return null;
}
?>