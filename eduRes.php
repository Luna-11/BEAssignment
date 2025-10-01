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

    <!-- Educational Resources Header - Reduced padding -->
    <section class="resources-header bg-gradient-to-r from-primary to-medium-pink text-white py-12 md:py-16">
        <div class="resources-hero text-center max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Educational Resources</h1>
            <p class="text-xl opacity-90">Expand your culinary knowledge with our comprehensive educational materials, infographics, and learning guides</p>
        </div>
    </section>

    <!-- Nutrition Education Section - Reduced padding -->
    <section class="educational-section py-12 md:py-16" id="nutrition-section">
        <div class="section-container max-w-6xl mx-auto px-4">
            
            <?php
            include('./configMysql.php');

            // Get all Educational resources (resourceTypeID = 2)
            $sql = "SELECT resourceID, resourceName, description, resourcesImage, PDF_file, Video 
                    FROM Resource 
                    WHERE resourceTypeID = 2
                    ORDER BY resourceID DESC";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="education-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        // Extract just the filename from the stored path
                        $imagePath = $row['resourcesImage'];
                        $pdfPath = $row['PDF_file'];
                        $videoPath = $row['Video'];
                        
                        // Get just the filename (without the directory path)
                        $imageFile = basename($imagePath);
                        $pdfFile = $pdfPath ? basename($pdfPath) : null;
                        $videoFile = $videoPath ? basename($videoPath) : null;
                    ?>
                        <div class="education-card bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                            <div class="education-image relative overflow-hidden">
                                <?php if (!empty($imagePath)): ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="<?php echo htmlspecialchars($row['resourceName']); ?>" 
                                         class="w-full h-48 object-cover cursor-zoom-in"
                                         onclick="openImageLightbox('<?php echo htmlspecialchars($imagePath); ?>', '<?php echo htmlspecialchars($row['resourceName']); ?>', <?php echo $row['resourceID']; ?>)">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-book text-4xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="education-overlay absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 transition-opacity duration-300">
                                    <!-- Download Image Button -->
                                    <a href="<?php echo htmlspecialchars($imagePath); ?>" 
                                       class="download-btn bg-primary text-white px-4 py-2 rounded-full font-semibold transition-all duration-300 hover:bg-medium-pink mr-2" 
                                       download="<?php echo htmlspecialchars($row['resourceName']); ?>_image.jpg">
                                        <i class="fas fa-download mr-2"></i> Image
                                    </a>
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
                                    <!-- View Detail Button -->
                                    <button class="view-detail-btn bg-light-pink text-text px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink hover:text-white flex items-center"
                                            onclick="viewResourceDetail(<?php echo $row['resourceID']; ?>)">
                                        <i class="fas fa-eye mr-2"></i> View Detail
                                    </button>
                                    
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
                <p class="text-center text-text/80 py-8">No educational resources found yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Detail Modal -->
    <div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
            <div class="modal-header flex justify-between items-center p-6 border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-xl">
                <h2 id="modalTitle" class="text-2xl font-bold text-text"></h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-content p-6">
                <!-- Image Section with Download Button -->
                <div id="imageSection" class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-text">Image</h3>
                        <button id="modalImageDownload" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center">
                            <i class="fas fa-download mr-2"></i> Download Image
                        </button>
                    </div>
                    <img id="modalImage" src="" alt="" 
                         class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg cursor-zoom-in"
                         onclick="openImageLightboxFromModal(this.src, this.alt)">
                </div>
                
                <!-- Description Section -->
                <div class="description-section mb-6">
                    <h3 class="text-lg font-semibold text-text mb-3">Description</h3>
                    <p id="modalDescription" class="text-text/80 leading-relaxed whitespace-pre-line"></p>
                </div>
                
                <!-- Video Section with Download Button -->
                <div id="videoSection" class="mb-6 hidden">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-text">Video</h3>
                        <button id="modalVideoDownload" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center">
                            <i class="fas fa-download mr-2"></i> Download Video
                        </button>
                    </div>
                    <video id="modalVideo" controls class="w-full rounded-lg shadow-lg">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- PDF Section with Download Button -->
                <div id="pdfSection" class="mb-6 hidden">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-text">Document</h3>
                        <button id="modalPdfDownload" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center">
                            <i class="fas fa-download mr-2"></i> Download PDF
                        </button>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg border-2 border-dashed border-gray-300 text-center">
                        <i class="fas fa-file-pdf text-4xl text-red-500 mb-2"></i>
                        <p class="text-text/80">PDF Document Available</p>
                        <p class="text-text/60 text-sm mt-1">Click the download button to get the PDF file</p>
                    </div>
                </div>

                <!-- Download All Section -->
                <div class="download-all-section mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-text mb-2">Download All Files</h3>
                            <p class="text-text/60 text-sm">Get all available files for this resource</p>
                        </div>
                        <button id="modalDownloadAll" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:bg-medium-pink flex items-center">
                            <i class="fas fa-download mr-2"></i> Download All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Image Lightbox Modal - Higher z-index to ensure it's on top -->
    <div id="imageLightbox" class="fixed inset-0 bg-black bg-opacity-90 z-[10000] hidden flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2" style="height: 70vh;">
            <!-- Close Button -->
            <button onclick="closeImageLightbox()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 text-2xl z-10 bg-white bg-opacity-90 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Download Button -->
            <button id="lightboxDownload" class="absolute top-4 right-16 text-gray-600 hover:text-gray-800 text-xl z-10 bg-white bg-opacity-90 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110">
                <i class="fas fa-download"></i>
            </button>
            
            <!-- Image Container -->
            <div class="w-full h-full flex items-center justify-center bg-gray-100 p-4" style="height: calc(100% - 80px);">
                <img id="lightboxImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            </div>
            
            <!-- Image Info -->
            <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
                <p id="lightboxTitle" class="text-text font-semibold text-lg text-center"></p>
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
                
                // Extract filenames from paths
                $imagePath = $row['resourcesImage'];
                $pdfPath = $row['PDF_file'];
                $videoPath = $row['Video'];
                
                echo $row['resourceID'] . ": {";
                echo "name: '" . addslashes($row['resourceName']) . "',";
                echo "description: `" . addslashes($row['description']) . "`,";
                echo "image: '" . addslashes($imagePath) . "',";
                echo "video: '" . addslashes($videoPath) . "',";
                echo "pdf: '" . addslashes($pdfPath) . "'";
                echo "}";
            endwhile;
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

        // For modal images
        function openImageLightboxFromModal(imageSrc, imageAlt) {
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
            const imageSection = document.getElementById('imageSection');
            const pdfSection = document.getElementById('pdfSection');
            const modalImageDownload = document.getElementById('modalImageDownload');
            const modalVideoDownload = document.getElementById('modalVideoDownload');
            const modalPdfDownload = document.getElementById('modalPdfDownload');
            const modalDownloadAll = document.getElementById('modalDownloadAll');
            
            // Set modal content
            modalTitle.textContent = resource.name;
            modalDescription.textContent = resource.description;
            
            // Set image and download button
            if (resource.image) {
                modalImage.src = resource.image;
                modalImage.alt = resource.name;
                imageSection.classList.remove('hidden');
                
                // Set image download
                modalImageDownload.onclick = function() {
                    downloadFile(resource.image, resource.name + '_image.jpg');
                };
            } else {
                imageSection.classList.add('hidden');
            }
            
            // Set video and download button
            if (resource.video) {
                modalVideo.src = resource.video;
                videoSection.classList.remove('hidden');
                
                // Set video download
                modalVideoDownload.onclick = function() {
                    downloadFile(resource.video, resource.name + '_video.mp4');
                };
            } else {
                videoSection.classList.add('hidden');
            }

            // Set PDF and download button
            if (resource.pdf) {
                pdfSection.classList.remove('hidden');
                
                // Set PDF download
                modalPdfDownload.onclick = function() {
                    downloadFile(resource.pdf, resource.name + '_document.pdf');
                };
            } else {
                pdfSection.classList.add('hidden');
            }

            // Set download all button
            modalDownloadAll.onclick = function() {
                downloadAllFiles(resourceId);
            };
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
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
        
        /* Smooth transitions for lightbox */
        #imageLightbox {
            transition: opacity 0.3s ease;
        }
        
        #lightboxImage {
            transition: transform 0.3s ease;
        }
        
        /* Ensure modal header stays on top of content but lightbox stays on top of everything */
        .modal-header {
            z-index: 10;
        }
    </style>
</body>
</html>