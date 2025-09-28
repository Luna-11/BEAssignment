<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resources - FoodFusion</title>
    
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
    <section class="resources-header bg-gradient-to-r from-primary to-medium-pink text-white py-20">
        <div class="resources-hero text-center max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Educational Resources</h1>
            <p class="text-xl opacity-90">Expand your culinary knowledge with our comprehensive educational materials, infographics, and learning guides</p>
        </div>
    </section>

    <!-- Nutrition Education Section -->
    <section class="educational-section py-16" id="nutrition-section">
        <div class="section-container max-w-6xl mx-auto px-4">
            
            <?php
            include('./configMysql.php');

            // Get all Culinary resources (resourceTypeID = 2)
            $sql = "SELECT r.resourceID, r.resourceName, r.description, r.resourcesImage, r.Video, f.filename AS pdfFile
                    FROM Resource r
                    LEFT JOIN files f ON r.resourceID = f.resourcesID
                    WHERE r.resourceTypeID = 2
                    ORDER BY r.resourceID DESC";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="education-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="education-card bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                            <div class="education-image relative overflow-hidden">
                                <img src="uploads/resources/images/<?php echo htmlspecialchars($row['resourcesImage']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['resourceName']); ?>" 
                                     class="w-full h-48 object-cover">
                                <div class="education-overlay absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 transition-opacity duration-300">
                                    <?php if (!empty($row['pdfFile'])): ?>
                                        <a href="uploads/resources/pdfs/<?php echo htmlspecialchars($row['pdfFile']); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink mr-2" download>
                                            <i class="fas fa-download mr-2"></i> PDF
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($row['Video'])): ?>
                                        <a href="uploads/resources/videos/<?php echo htmlspecialchars($row['Video']); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink" target="_blank">
                                            <i class="fas fa-play mr-2"></i> Video
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="education-content p-6">
                                <h3 class="text-xl font-semibold text-text mb-3"><?php echo htmlspecialchars($row['resourceName']); ?></h3>
                                <p class="text-text/80 mb-4 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?></p>
                                
                                <!-- Action Buttons -->
                                <div class="action-buttons flex justify-between mt-4">
                                    <!-- View Detail Button -->
                                    <button class="view-detail-btn bg-light-pink text-text px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink hover:text-white flex items-center"
                                            onclick="viewResourceDetail(<?php echo $row['resourceID']; ?>)">
                                        <i class="fas fa-eye mr-2"></i> View Detail
                                    </button>
                                    
                                    <!-- Download Button -->
                                    <?php if (!empty($row['pdfFile'])): ?>
                                        <a href="uploads/resources/pdfs/<?php echo htmlspecialchars($row['pdfFile']); ?>" 
                                           class="download-btn bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center" 
                                           download>
                                            <i class="fas fa-download mr-2"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <button class="download-btn bg-gray-400 text-white px-4 py-2 rounded-lg font-semibold flex items-center cursor-not-allowed" disabled>
                                            <i class="fas fa-download mr-2"></i> Download
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-text/80 py-8">No culinary resources found yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Detail Modal -->
    <div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="modal-header flex justify-between items-center p-6 border-b border-gray-200">
                <h2 id="modalTitle" class="text-2xl font-bold text-text"></h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-content p-6">
                <!-- Image Section -->
                <div id="imageSection" class="mb-6">
                    <img id="modalImage" src="" alt="" class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                </div>
                
                <!-- Description Section -->
                <div class="description-section mb-6">
                    <h3 class="text-lg font-semibold text-text mb-3">Description</h3>
                    <p id="modalDescription" class="text-text/80 leading-relaxed whitespace-pre-line"></p>
                </div>
                
                <!-- Video Section -->
                <div id="videoSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold text-text mb-3">Video</h3>
                    <video id="modalVideo" controls class="w-full rounded-lg shadow-lg">
                        Your browser does not support the video tag.
                    </video>
                </div>
                
                <!-- Download Section -->
                <div id="downloadSection" class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-text mb-2">Download Resource</h3>
                        <p class="text-text/60 text-sm">Get the complete resource for offline use</p>
                    </div>
                    <a id="modalDownloadBtn" href="#" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center hidden" download>
                        <i class="fas fa-download mr-2"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- JavaScript Files -->
    <script src="eduRes.js"></script>
    
    <script>
        // Store resource data in a JavaScript object
        const resourceData = {
            <?php
            mysqli_data_seek($result, 0); // Reset result pointer
            $first = true;
            while ($row = mysqli_fetch_assoc($result)):
                if (!$first) echo ",";
                $first = false;
                echo $row['resourceID'] . ": {";
                echo "name: '" . addslashes($row['resourceName']) . "',";
                echo "description: `" . addslashes($row['description']) . "`,";
                echo "image: '" . addslashes($row['resourcesImage']) . "',";
                echo "video: '" . addslashes($row['Video']) . "',";
                echo "pdf: '" . addslashes($row['pdfFile']) . "'";
                echo "}";
            endwhile;
            ?>
        };

        // Function to open modal with resource details
        function viewResourceDetail(resourceId) {
            const resource = resourceData[resourceId];
            if (!resource) {
                console.error('Resource not found:', resourceId);
                return;
            }

            const modal = document.getElementById('resourceModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalImage = document.getElementById('modalImage');
            const modalDescription = document.getElementById('modalDescription');
            const modalVideo = document.getElementById('modalVideo');
            const videoSection = document.getElementById('videoSection');
            const modalDownloadBtn = document.getElementById('modalDownloadBtn');
            const imageSection = document.getElementById('imageSection');
            const downloadSection = document.getElementById('downloadSection');
            
            // Set modal content
            modalTitle.textContent = resource.name;
            modalDescription.textContent = resource.description;
            
            // Set image
            if (resource.image) {
                modalImage.src = 'uploads/resources/images/' + resource.image;
                modalImage.alt = resource.name;
                imageSection.classList.remove('hidden');
            } else {
                imageSection.classList.add('hidden');
            }
            
            // Set video
            if (resource.video) {
                modalVideo.src = 'uploads/resources/videos/' + resource.video;
                videoSection.classList.remove('hidden');
            } else {
                videoSection.classList.add('hidden');
            }
            
            // Set download button
            if (resource.pdf) {
                modalDownloadBtn.href = 'uploads/resources/pdfs/' + resource.pdf;
                modalDownloadBtn.classList.remove('hidden');
                downloadSection.classList.remove('hidden');
            } else {
                modalDownloadBtn.classList.add('hidden');
                downloadSection.classList.add('hidden');
            }
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        // Function to close modal
        function closeModal() {
            const modal = document.getElementById('resourceModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset video
            const modalVideo = document.getElementById('modalVideo');
            if (modalVideo) {
                modalVideo.pause();
                modalVideo.currentTime = 0;
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('resourceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
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
        /* Custom scrollbar for modal */
        #resourceModal .bg-white::-webkit-scrollbar {
            width: 8px;
        }
        
        #resourceModal .bg-white::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        #resourceModal .bg-white::-webkit-scrollbar-thumb {
            background: #C89091;
            border-radius: 10px;
        }
        
        #resourceModal .bg-white::-webkit-scrollbar-thumb:hover {
            background: #ddb2b1;
        }
        
        /* Line clamp for description preview */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>