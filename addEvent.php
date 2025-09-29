<?php
session_start();

echo "<!-- DEBUG: Session ID: " . session_id() . " -->";
echo "<!-- DEBUG: UserID: " . ($_SESSION['userID'] ?? 'NOT SET') . " -->";
if (!isset($_SESSION['userID'])) {
    header("Location: logIn.php");
    exit;
}

include('./configMysql.php');
$userID = $_SESSION['userID'];

$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $title = trim($_POST['title']);
    $eventDate = $_POST['eventDate'];
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    // Validate required fields
    if (empty($title) || empty($eventDate) || empty($location) || empty($description)) {
        $error = "Error: All fields are required.";
    }
    // Validate title length
    else if (strlen($title) > 50) {
        $error = "Error: Title must be 50 characters or less.";
    }
    // Validate location length
    else if (strlen($location) > 200) {
        $error = "Error: Location must be 200 characters or less.";
    }
    // Validate description length
    else if (strlen($description) > 500) {
        $error = "Error: Description must be 500 characters or less.";
    }
    // Validate date format and future date
    else {
        $currentDateTime = new DateTime();
        $eventDateTime = new DateTime($eventDate);
        
        if ($eventDateTime <= $currentDateTime) {
            $error = "Error: Event date must be in the future.";
        }
    }

    if (empty($error)) {
        // File upload settings - USE ABSOLUTE PATH
        $uploadDir = __DIR__ . "/uploads/events/"; 

        if (!is_dir($uploadDir)) {
            // Try to create directory with proper permissions
            if (!mkdir($uploadDir, 0755, true)) {
                $error = "Error: Could not create upload directory. ";
                $error .= "Tried to create: " . $uploadDir . ". ";
                $error .= "Please create the folder manually or check parent directory permissions.";
            } else {
                echo "<!-- DEBUG: Directory created successfully -->\n";
            }
        } else {
            echo "<!-- DEBUG: Directory already exists -->\n";
            echo "<!-- DEBUG: Directory writable: " . (is_writable($uploadDir) ? 'Yes' : 'No') . " -->\n";
        }

        if (empty($error)) {
            $eventImage = $_FILES['eventImage'];
            
            // DEBUG: Show file upload info
            echo "<!-- DEBUG: File upload error code: " . $eventImage['error'] . " -->\n";
            echo "<!-- DEBUG: File temp location: " . $eventImage['tmp_name'] . " -->\n";
            echo "<!-- DEBUG: File size: " . $eventImage['size'] . " -->\n";
            
            // Check if file was uploaded
            if ($eventImage['error'] !== UPLOAD_ERR_OK) {
                $uploadErrors = [
                    0 => 'There is no error, the file uploaded with success',
                    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                    3 => 'The uploaded file was only partially uploaded',
                    4 => 'No file was uploaded',
                    6 => 'Missing a temporary folder',
                    7 => 'Failed to write file to disk.',
                    8 => 'A PHP extension stopped the file upload.'
                ];
                $error = "Error: File upload failed - " . ($uploadErrors[$eventImage['error']] ?? 'Unknown error');
            }
            else {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($eventImage['tmp_name']);
                
                if (!in_array($fileType, $allowedTypes)) {
                    $error = "Error: Only JPG, PNG, GIF, or WebP files are allowed. Detected type: " . $fileType;
                }
                // Validate file size (max 5MB)
                else if ($eventImage['size'] > 5 * 1024 * 1024) {
                    $error = "Error: File size must be less than 5MB.";
                }
                else {
                    // Generate unique filename to prevent conflicts
                    $fileExtension = pathinfo($eventImage['name'], PATHINFO_EXTENSION);
                    $uniqueName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $targetFile = $uploadDir . $uniqueName;

                    // DEBUG: Check directory permissions
                    echo "<!-- DEBUG: Directory writable: " . (is_writable($uploadDir) ? 'Yes' : 'No') . " -->\n";
                    echo "<!-- DEBUG: Target file path: " . $targetFile . " -->\n";
                    
                    if (!is_writable($uploadDir)) {
                        $error = "Error: Upload directory is not writable. Please check folder permissions.";
                    }
                    else {
                        // Upload file
                        if (move_uploaded_file($eventImage['tmp_name'], $targetFile)) {
                            echo "<!-- DEBUG: File moved successfully -->\n";
                            
                            // FIX: Store the relative path in database instead of just filename
                            $imagePath = "uploads/events/" . $uniqueName;
                            
                            // Prepare SQL - matches your database schema exactly
                            $sql = "INSERT INTO event (title, eventDate, location, description, eventImage, userID) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                            
                            $stmt = $conn->prepare($sql);
                            if (!$stmt) {
                                $error = "Error preparing statement: " . $conn->error;
                            }
                            else {
                                // Use $imagePath instead of $uniqueName
                                $stmt->bind_param("sssssi", $title, $eventDate, $location, $description, $imagePath, $userID);

                                if ($stmt->execute()) {
                                    // Success - verify session is still valid before redirect
                                    if (!isset($_SESSION['userID'])) {
                                        // Session lost, redirect to login
                                        header("Location: logIn.php");
                                        exit();
                                    }
                                    
                                    // Session is valid, redirect to home page instead of event.php
                                    $_SESSION['success_message'] = "Event created successfully!";
    
                                    // Redirect to home page
                                    header("Location: index.php");
                                    exit();
                                } else {
                                    $error = "Error: Failed to create event. Database error: " . $stmt->error;
                                }
                                $stmt->close();
                            }
                        } else {
                            $error = "Error: Failed to upload image. Please check the following:\n";
                            $error .= "- Directory permissions\n";
                            $error .= "- Available disk space\n";
                            $error .= "- File path: " . $targetFile;
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event - FoodFusion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#C89091',
                        'text': '#7b4e48',
                        'lightest': '#fcfaf2',
                        'light-pink': '#e9d0cb',
                        'medium-pink': '#ddb2b1',
                        'light-yellow': '#f9f1e5',
                        'light-gray': '#bbb',
                        'medium-gray': '#555',
                        'shadow': 'rgba(0,0,0,0.1)',
                        'border': '#ccc',
                        'button': '#333',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Custom focus styles */
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(200, 144, 145, 0.25);
        }
        
        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .error-message {
            background-color: #fed7d7;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background-color: #c6f6d5;
            border: 1px solid #9ae6b4;
            color: #2d774a;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        /* File upload drag and drop styles */
        .file-upload-area {
            transition: all 0.3s ease;
        }
        
        .file-upload-area.dragover {
            background-color: #e9d0cb;
            border-color: #C89091;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-light-pink to-light-yellow">
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 mt-4">
        <div class="max-w-2xl mx-auto bg-lightest rounded-xl shadow-lg p-6 md:p-8 border border-light-pink">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-text mb-2">Create New Event</h2>
            <p class="text-center text-medium-gray mb-6">Share your culinary event with the FoodFusion community</p>
            
            <!-- Error Message Display -->
            <?php if (!empty($error)): ?>
                <div class="error-message mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="addEvent.php" method="POST" enctype="multipart/form-data" id="eventForm">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block font-medium text-text mb-2">
                        <i class="fas fa-heading mr-2"></i>Event Title
                    </label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-border rounded-lg focus:border-primary transition-all" 
                           id="title" name="title" maxlength="50" required
                           placeholder="Enter event title (e.g., Summer BBQ Cooking Class)"
                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    <div class="text-right text-light-gray text-sm mt-1">
                        <span id="titleCount">0</span>/50 characters
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="mb-6">
                    <label for="eventDate" class="block font-medium text-text mb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Event Date & Time
                    </label>
                    <input type="datetime-local" 
                           class="w-full px-4 py-3 border border-border rounded-lg focus:border-primary transition-all" 
                           id="eventDate" name="eventDate" required
                           value="<?php echo isset($_POST['eventDate']) ? htmlspecialchars($_POST['eventDate']) : ''; ?>">
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block font-medium text-text mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Location
                    </label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-border rounded-lg focus:border-primary transition-all" 
                           id="location" name="location" maxlength="200" required
                           placeholder="Enter event location or online meeting link"
                           value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    <div class="text-right text-light-gray text-sm mt-1">
                        <span id="locationCount">0</span>/200 characters
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block font-medium text-text mb-2">
                        <i class="fas fa-file-alt mr-2"></i>Description
                    </label>
                    <textarea class="w-full px-4 py-3 border border-border rounded-lg focus:border-primary transition-all" 
                              id="description" name="description" rows="5" maxlength="500" required
                              placeholder="Describe your event - what will participants learn or experience?"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    <div class="text-right text-light-gray text-sm mt-1">
                        <span id="charCount">0</span>/500 characters
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-8">
                    <label for="eventImage" class="block font-medium text-text mb-2">
                        <i class="fas fa-image mr-2"></i>Event Image
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="eventImage" class="file-upload-area flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-light-pink rounded-lg cursor-pointer bg-light-yellow hover:bg-light-pink transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-text text-center">
                                    <span class="font-semibold">Click to upload</span><br>
                                    or drag and drop
                                </p>
                                <p class="text-xs text-medium-gray text-center">PNG, JPG, GIF, WebP (MAX. 5MB)</p>
                            </div>
                            <input id="eventImage" name="eventImage" type="file" class="hidden" accept="image/*" required />
                        </label>
                    </div>
                    <div id="fileName" class="text-center text-text mt-2 font-medium"></div>
                    <div id="fileError" class="text-red-500 text-sm mt-1 text-center hidden"></div>
                </div>

                <!-- Submit Button -->
                <div class="text-center space-y-4">
                    <button type="submit" 
                            class="bg-button hover:bg-primary text-white font-semibold py-3 px-8 rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-calendar-plus mr-2"></i>Create Event
                    </button>
                    <div>
                        <a href="event.php" class="text-primary hover:text-medium-pink transition-all inline-block mt-4">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Events
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        // Character counters
        const titleInput = document.getElementById('title');
        const titleCount = document.getElementById('titleCount');
        const locationInput = document.getElementById('location');
        const locationCount = document.getElementById('locationCount');
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        
        // Initialize counts
        titleCount.textContent = titleInput.value.length;
        locationCount.textContent = locationInput.value.length;
        charCount.textContent = descriptionTextarea.value.length;
        
        // Event listeners for character counting
        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });
        
        locationInput.addEventListener('input', function() {
            locationCount.textContent = this.value.length;
        });
        
        descriptionTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // File upload handling
        const fileInput = document.getElementById('eventImage');
        const fileNameDisplay = document.getElementById('fileName');
        const fileErrorDisplay = document.getElementById('fileError');
        const fileUploadArea = document.querySelector('.file-upload-area');
        const form = document.getElementById('eventForm');
        
        fileInput.addEventListener('change', function() {
            handleFileSelection(this.files[0]);
        });
        
        // Drag and drop functionality
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelection(e.dataTransfer.files[0]);
            }
        });
        
        function handleFileSelection(file) {
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    fileErrorDisplay.textContent = 'Invalid file type. Please upload an image (JPG, PNG, GIF, WebP).';
                    fileErrorDisplay.classList.remove('hidden');
                    fileNameDisplay.textContent = '';
                    fileInput.value = '';
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    fileErrorDisplay.textContent = 'File size too large. Please select a file smaller than 5MB.';
                    fileErrorDisplay.classList.remove('hidden');
                    fileNameDisplay.textContent = '';
                    fileInput.value = '';
                    return;
                }
                
                // Valid file
                fileErrorDisplay.classList.add('hidden');
                fileNameDisplay.textContent = 'Selected file: ' + file.name;
                fileNameDisplay.classList.add('text-green-600');
            }
        }
        
        // Set minimum date to current date/time
        const today = new Date();
        // Add 1 hour to current time as minimum
        today.setHours(today.getHours() + 1);
        const minDate = today.toISOString().slice(0, 16);
        document.getElementById('eventDate').min = minDate;
        
        // Form validation before submit
        form.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (!file) {
                e.preventDefault();
                fileErrorDisplay.textContent = 'Please select an image file.';
                fileErrorDisplay.classList.remove('hidden');
                return;
            }
            
            // Additional client-side validation can be added here
        });
        
        // Auto-remove error messages after 5 seconds
        setTimeout(() => {
            const errorDiv = document.querySelector('.error-message');
            if (errorDiv) {
                errorDiv.style.opacity = '0';
                setTimeout(() => errorDiv.remove(), 300);
            }
        }, 5000);
    </script>
</body>
</html>