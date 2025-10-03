<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinary Resources - FoodFusion</title>
    
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
</head>
<body class="bg-lightest">
    <!-- Navigation Bar -->
    <?php include('navbar.php'); ?>

    <!-- Educational Resources Header -->
    <section class="resources-header bg-gradient-to-r from-primary to-medium-pink text-white py-12 md:py-16">
        <div class="resources-hero text-center max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Culinary Resources</h1>
            <p class="text-xl opacity-90">Expand your culinary knowledge with our comprehensive educational materials, infographics, and learning guides</p>
        </div>
    </section>

    <!--Education Section -->
    <section class="educational-section py-12 md:py-16" id="nutrition-section">
        <div class="section-container max-w-6xl mx-auto px-4">
            
            <?php
            include('./configMysql.php');

           
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Corrected SQL query - use direct columns from Resource table
            $sql = "SELECT resourceID, resourceName, description, resourcesImage, PDF_file, Video 
                    FROM Resource 
                    WHERE resourceTypeID = 1
                    ORDER BY resourceID DESC";

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "<p class='text-center text-red-500 py-8'>Error executing query: " . mysqli_error($conn) . "</p>";
            } else if (mysqli_num_rows($result) > 0): ?>
            
                <div class="education-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        // Use the full stored paths directly
                        $imagePath = $row['resourcesImage'];
                        $pdfPath = $row['PDF_file'];
                        $videoPath = $row['Video'];
                    ?>
                        <div class="education-card bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                            <div class="education-image relative overflow-hidden">
                                <?php if (!empty($imagePath)): ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="<?php echo htmlspecialchars($row['resourceName']); ?>" 
                                         class="w-full h-48 object-cover">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-utensils text-4xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="education-overlay absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 transition-opacity duration-300">
                                    <!-- Download Image Button -->
                                    <?php if (!empty($imagePath)): ?>
                                        <a href="<?php echo htmlspecialchars($imagePath); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink mr-2" 
                                           download="<?php echo htmlspecialchars($row['resourceName']); ?>_image.jpg">
                                            <i class="fas fa-download mr-2"></i> Image
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($videoPath)): ?>
                                        <a href="<?php echo htmlspecialchars($videoPath); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink mr-2" 
                                           download="<?php echo htmlspecialchars($row['resourceName']); ?>_video.mp4">
                                            <i class="fas fa-download mr-2"></i> Video
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($pdfPath)): ?>
                                        <a href="<?php echo htmlspecialchars($pdfPath); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink" 
                                           download="<?php echo htmlspecialchars($row['resourceName']); ?>_document.pdf">
                                            <i class="fas fa-download mr-2"></i> PDF
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="education-content p-6">
                                <h3 class="text-xl font-semibold text-text mb-3"><?php echo htmlspecialchars($row['resourceName']); ?></h3>
                                <p class="text-text/80 mb-4 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?></p>
                                
                                <!-- Action Buttons -->
                                <div class="action-buttons flex justify-between mt-4">
                                    <!-- View Detail Button - Now links to detail page -->
                                    <a href="culResDetail.php?id=<?php echo $row['resourceID']; ?>" 
                                       class="view-detail-btn bg-light-pink text-text px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink hover:text-white flex items-center">
                                        <i class="fas fa-eye mr-2"></i> View Detail
                                    </a>
                                    
                                    <!-- Download All Button -->
                                    <button class="download-all-btn bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center"
                                            onclick="downloadAllFiles(<?php echo $row['resourceID']; ?>)">
                                        <i class="fas fa-download mr-2"></i> Download All
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-text/80 py-8">No culinary resources found yet.</p>
            <?php endif; 
            
            // Store result for JavaScript use
            $resources_data = [];
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) {
                $resources_data[$row['resourceID']] = $row;
            }
            
            // Close connection
            mysqli_close($conn);
            ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <script>
        // Store resource data in a JavaScript object
        const resourceData = {
            <?php
            $first = true;
            foreach ($resources_data as $resourceId => $row):
                if (!$first) echo ",";
                $first = false;
                echo $resourceId . ": {";
                echo "name: '" . addslashes($row['resourceName']) . "',";
                echo "description: `" . addslashes($row['description']) . "`,";
                echo "image: '" . addslashes($row['resourcesImage']) . "',";
                echo "video: '" . addslashes($row['Video']) . "',";
                echo "pdf: '" . addslashes($row['PDF_file']) . "'";
                echo "}";
            endforeach;
            ?>
        };

        // Lightbox functionality - Single image only
        function openImageLightbox(imageSrc, imageAlt, resourceId) {
            const lightbox = document.getElementById('imageLightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            const lightboxDownload = document.getElementById('lightboxDownload');
            const lightboxTitle = document.getElementById('lightboxTitle');
            
            // Set image
            lightboxImage.src = imageSrc;
            lightboxImage.alt = imageAlt;
            lightboxTitle.textContent = imageAlt;
            
            // Set download functionality
            lightboxDownload.onclick = function() {
                const link = document.createElement('a');
                link.href = imageSrc;
                link.download = imageAlt + '_fullsize.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
            
            // Show lightbox
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageLightbox() {
            const lightbox = document.getElementById('imageLightbox');
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageLightbox();
            }
        });

        // Close lightbox when clicking on background
        document.getElementById('imageLightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageLightbox();
            }
        });

        // Helper function to download files
        function downloadFile(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Function to download all files (image, video, PDF)
        function downloadAllFiles(resourceId) {
            const resource = resourceData[resourceId];
            if (!resource) {
                console.error('Resource not found:', resourceId);
                return;
            }

            // Download image
            if (resource.image) {
                downloadFile(resource.image, resource.name + '_image.jpg');
            }

            // Download video if exists
            if (resource.video) {
                setTimeout(() => {
                    downloadFile(resource.video, resource.name + '_video.mp4');
                }, 500); // Small delay to avoid browser blocking multiple downloads
            }

            // Download PDF if exists
            if (resource.pdf) {
                setTimeout(() => {
                    downloadFile(resource.pdf, resource.name + '_document.pdf');
                }, 1000); // Additional delay for third download
            }
        }
        
        // Optional: Add hover effect to show overlay on image
        document.addEventListener('DOMContentLoaded', function() {
            const educationCards = document.querySelectorAll('.education-card');
            
            educationCards.forEach(card => {
                const image = card.querySelector('.education-image');
                const overlay = card.querySelector('.education-overlay');
                
                if (image && overlay) {
                    image.addEventListener('mouseenter', () => {
                        overlay.classList.remove('opacity-0');
                    });
                    
                    image.addEventListener('mouseleave', () => {
                        overlay.classList.add('opacity-0');
                    });
                }
            });
        });
    </script>

    <style>
        /* Line clamp for description preview */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth transitions for lightbox */
        #imageLightbox {
            transition: opacity 0.3s ease;
        }
        
        #lightboxImage {
            transition: transform 0.3s ease;
        }
    </style>
</body>
</html>