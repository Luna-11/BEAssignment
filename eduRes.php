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
                                <p class="text-text/80"><?php echo htmlspecialchars($row['description']); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-text/80 py-8">No culinary resources found yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- JavaScript Files -->
    <script src="eduRes.js"></script>
</body>
</html>