<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinary Resource Details - FoodFusion</title>
    
    <!-- Tailwind CSS with your custom colors -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                        'white': '#fff',
                        'black': '#222',
                        'shadow-color': 'rgba(0,0,0,0.1)',
                        'border-color': '#ccc',
                        'button-color': '#333',
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #C89091;
            --text-color: #7b4e48;
            --lightest-color: #fcfaf2;
            --light_pink: #e9d0cb;
            --medium_pink: #ddb2b1;
            --light_yellow: #f9f1e5;
            --white: #fff;
            --black: #222;
            --light-gray: #bbb;
            --medium-gray: #555;
            --shadow-color: rgba(0,0,0,0.1);
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--lightest-color);
            color: var(--text-color);
        }
        .title-font {
            font-family: 'Playfair Display', serif;
        }
        .debug-info {
            background: #f8f9fa;
            border-left: 4px solid #C89091;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
        .full-height-image {
            height: 100%;
            min-height: 600px;
        }
        @media (max-width: 768px) {
            .full-height-image {
                min-height: 400px;
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .bg-lightest, .bg-white {
                background: white !important;
            }
            .text-text, .text-primary {
                color: black !important;
            }
            .shadow-lg, .rounded-2xl {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .full-height-image {
                min-height: 300px !important;
            }
        }
    </style>
</head>
<body class="bg-lightest">
    <!-- Navigation Bar -->
    <?php include('navbar.php'); ?>

    <?php
    include('./configMysql.php');
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get resource ID from URL parameter
    $resourceID = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Debug: Show what ID we're looking for
    echo "<!-- Debug: Looking for resource ID: $resourceID -->";
    
    // Initialize variables
    $resource = null;
    
    // Fetch resource data from database - REMOVED createdDate since it doesn't exist
    $sql = "SELECT resourceID, resourceName, description, resourcesImage, PDF_file, Video, resourceTypeID
            FROM Resource 
            WHERE resourceID = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo "<!-- Debug: Prepare failed: " . $conn->error . " -->";
        $resource = null;
    } else {
        $stmt->bind_param("i", $resourceID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $resource = $result->fetch_assoc();
                echo "<!-- Debug: Resource found: " . htmlspecialchars($resource['resourceName'] ?? 'No name') . " -->";
                echo "<!-- Debug: PDF_file: " . htmlspecialchars($resource['PDF_file'] ?? 'No PDF') . " -->";
                echo "<!-- Debug: Image: " . htmlspecialchars($resource['resourcesImage'] ?? 'No image') . " -->";
                echo "<!-- Debug: Video: " . htmlspecialchars($resource['Video'] ?? 'No video') . " -->";
            } else {
                $resource = null;
                echo "<!-- Debug: No resource found with ID: $resourceID -->";
            }
        } else {
            echo "<!-- Debug: Execute failed: " . $stmt->error . " -->";
            $resource = null;
        }
        $stmt->close();
    }
    
    // Debug: Let's check what resources exist
    $debug_sql = "SELECT resourceID, resourceName FROM Resource LIMIT 10";
    $debug_result = $conn->query($debug_sql);
    if ($debug_result && $debug_result->num_rows > 0) {
        echo "<!-- Debug: Available resources: ";
        while ($debug_row = $debug_result->fetch_assoc()) {
            echo "ID: " . $debug_row['resourceID'] . " - " . $debug_row['resourceName'] . " | ";
        }
        echo " -->";
    } else {
        echo "<!-- Debug: No resources found in database -->";
    }
    
    // Close connection at the END
    $conn->close();
    ?>
    
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Debug Info (remove in production) -->
        <?php if (!$resource): ?>
        <div class="debug-info mb-4">
            <strong>Debug Information:</strong><br>
            Resource ID from URL: <?php echo $resourceID; ?><br>
            Resource Found: <?php echo $resource ? 'Yes' : 'No'; ?><br>
            SQL Query: SELECT resourceID, resourceName, description, resourcesImage, PDF_file, Video, resourceTypeID FROM Resource WHERE resourceID = <?php echo $resourceID; ?><br>
            Check if you're passing the correct ID in the URL: resource-detail.php?id=1<br>
            Available Resource IDs: 
            <?php
            // Reconnect to show available IDs
            $conn_temp = new mysqli($servername, $username, $password, $dbname);
            if (!$conn_temp->connect_error) {
                $ids_sql = "SELECT resourceID FROM Resource ORDER BY resourceID";
                $ids_result = $conn_temp->query($ids_sql);
                if ($ids_result && $ids_result->num_rows > 0) {
                    $ids = [];
                    while ($id_row = $ids_result->fetch_assoc()) {
                        $ids[] = $id_row['resourceID'];
                    }
                    echo implode(', ', $ids);
                } else {
                    echo 'No resources in database';
                }
                $conn_temp->close();
            }
            ?>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <header class="text-center mb-10">
            <?php if ($resource): ?>
                <h1 class="title-font text-4xl md:text-5xl font-bold text-text mb-4">
                    <?php echo htmlspecialchars($resource['resourceName'] ?? 'Untitled Resource'); ?>
                </h1>
                <p class="text-text text-lg">Educational Resource</p>
            <?php else: ?>
                <h1 class="title-font text-4xl md:text-5xl font-bold text-text mb-4">Resource Not Found</h1>
                <p class="text-text text-lg">The requested resource could not be found.</p>
            <?php endif; ?>
        </header>

        <?php if ($resource): ?>
        <!-- Resource Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Full Height Image Section -->
                <div class="md:w-2/5 p-0">
                    <div class="full-height-image relative bg-light-pink">
                        <div class="w-full h-full flex items-center justify-center overflow-hidden">
                            <?php if (!empty($resource['resourcesImage'])): ?>
                                <img src="<?php echo htmlspecialchars($resource['resourcesImage']); ?>" 
                                     alt="<?php echo htmlspecialchars($resource['resourceName']); ?>" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-medium-pink">
                                    <i class="fas fa-file-alt text-primary text-8xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-primary text-white px-4 py-2 rounded-full shadow-lg">
                            <span class="font-bold">
                                <?php 
                                $resourceType = $resource['resourceTypeID'] ?? 0;
                                echo $resourceType == 1 ? 'Nutrition Education' : 'Resource';
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="md:w-3/5 p-8">
                    <!-- Resource Info -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                        <div class="text-center">
                            <div class="text-primary mb-1">
                                <i class="fas fa-hashtag text-2xl"></i>
                            </div>
                            <div class="font-bold text-text">
                                ID: <?php echo htmlspecialchars($resource['resourceID']); ?>
                            </div>
                            <div class="text-sm text-text">Resource ID</div>
                        </div>
                        <div class="text-center">
                            <div class="text-primary mb-1">
                                <i class="fas fa-tag text-2xl"></i>
                            </div>
                            <div class="font-bold text-text">
                                <?php 
                                $resourceType = $resource['resourceTypeID'] ?? 0;
                                echo $resourceType == 1 ? 'Nutrition Education' : 'General Resource';
                                ?>
                            </div>
                            <div class="text-sm text-text">Resource Type</div>
                        </div>
                        <div class="text-center">
                            <div class="text-primary mb-1">
                                <i class="fas fa-file text-2xl"></i>
                            </div>
                            <div class="font-bold text-text">
                                <?php 
                                $fileCount = 0;
                                if (!empty($resource['resourcesImage'])) $fileCount++;
                                if (!empty($resource['PDF_file'])) $fileCount++;
                                if (!empty($resource['Video'])) $fileCount++;
                                echo $fileCount;
                                ?>
                            </div>
                            <div class="text-sm text-text">Files Available</div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="title-font text-2xl font-bold text-text mb-4 border-b border-light-pink pb-2">Description</h2>
                        <div class="text-text leading-relaxed whitespace-pre-line">
                            <?php 
                            if (!empty($resource['description'])) {
                                echo htmlspecialchars($resource['description']);
                            } else {
                                echo '<p class="text-primary text-center py-4">No description available</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Available Files -->
                    <div class="mb-8">
                        <h2 class="title-font text-2xl font-bold text-text mb-4 border-b border-light-pink pb-2">Available Files</h2>
                        <div class="space-y-4">
                            <?php if (!empty($resource['resourcesImage'])): ?>
                                <div class="flex items-center justify-between p-4 bg-light-yellow rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-image text-primary text-xl mr-3"></i>
                                        <span class="text-text font-medium">Image File</span>
                                    </div>
                                    <a href="<?php echo htmlspecialchars($resource['resourcesImage']); ?>" 
                                       class="bg-primary text-white px-4 py-2 rounded-lg font-medium transition duration-300 hover:bg-medium-pink flex items-center"
                                       download="<?php echo htmlspecialchars($resource['resourceName']); ?>_image.jpg">
                                        <i class="fas fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($resource['PDF_file'])): ?>
                                <div class="flex items-center justify-between p-4 bg-light-yellow rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-primary text-xl mr-3"></i>
                                        <span class="text-text font-medium">PDF Document</span>
                                    </div>
                                    <a href="<?php echo htmlspecialchars($resource['PDF_file']); ?>" 
                                       class="bg-primary text-white px-4 py-2 rounded-lg font-medium transition duration-300 hover:bg-medium-pink flex items-center"
                                       download="<?php echo htmlspecialchars($resource['resourceName']); ?>_document.pdf">
                                        <i class="fas fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($resource['Video'])): ?>
                                <div class="flex items-center justify-between p-4 bg-light-yellow rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-primary text-xl mr-3"></i>
                                        <span class="text-text font-medium">Video File</span>
                                    </div>
                                    <a href="<?php echo htmlspecialchars($resource['Video']); ?>" 
                                       class="bg-primary text-white px-4 py-2 rounded-lg font-medium transition duration-300 hover:bg-medium-pink flex items-center"
                                       download="<?php echo htmlspecialchars($resource['resourceName']); ?>_video.mp4">
                                        <i class="fas fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (empty($resource['resourcesImage']) && empty($resource['PDF_file']) && empty($resource['Video'])): ?>
                                <div class="text-primary text-center py-4">No files available for download</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4">
                        <button onclick="window.print()" class="flex-1 bg-primary hover:bg-medium-pink text-white py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                            <i class="fas fa-print mr-2"></i> Print Resource
                        </button>
                        
                        <!-- Download All Button -->
                        <button onclick="downloadAllFiles()" class="flex-1 border border-primary text-primary hover:bg-light-yellow py-3 px-4 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i> Download All
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-10 text-text text-sm">
            <p>
                © <?php echo date('Y'); ?> <?php echo htmlspecialchars($resource['resourceName']); ?> | 
                Made with <i class="fas fa-heart text-primary"></i>
            </p>
        </footer>
        <?php else: ?>
        <!-- Error Message -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <i class="fas fa-exclamation-triangle text-primary text-5xl mb-4"></i>
            <h2 class="text-2xl font-bold text-text mb-4">Resource Not Found</h2>
            <p class="text-text mb-6">The resource you're looking for doesn't exist or has been removed.</p>
            <a href="culinary-resources.php" class="bg-primary hover:bg-medium-pink text-white py-3 px-6 rounded-lg font-medium transition duration-300 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Resources
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <script>
        // Function to download all files
        function downloadAllFiles() {
            <?php if ($resource): ?>
                // Download image
                <?php if (!empty($resource['resourcesImage'])): ?>
                    downloadFile('<?php echo $resource['resourcesImage']; ?>', '<?php echo $resource['resourceName']; ?>_image.jpg');
                <?php endif; ?>
                
                // Download PDF if exists
                <?php if (!empty($resource['PDF_file'])): ?>
                    setTimeout(() => {
                        downloadFile('<?php echo $resource['PDF_file']; ?>', '<?php echo $resource['resourceName']; ?>_document.pdf');
                    }, 500);
                <?php endif; ?>
                
                // Download video if exists
                <?php if (!empty($resource['Video'])): ?>
                    setTimeout(() => {
                        downloadFile('<?php echo $resource['Video']; ?>', '<?php echo $resource['resourceName']; ?>_video.mp4');
                    }, 1000);
                <?php endif; ?>
            <?php endif; ?>
        }
        
        // Helper function to download files
        function downloadFile(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>