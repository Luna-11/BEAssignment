<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resources - FoodFusion</title>
    <link rel="stylesheet" href="homeStyle.css">
    <link rel="stylesheet" href="additionalStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include('navbar.php'); ?>

    <!-- Educational Resources Header -->
    <section class="resources-header">
        <div class="resources-hero">
            <h1>Educational Resources</h1>
            <p>Expand your culinary knowledge with our comprehensive educational materials, infographics, and learning guides</p>
        </div>
    </section>

    <!-- Learning Categories -->
    <section class="learning-categories">
        <div class="categories-container">
            <div class="category-nav">
                <button class="category-btn active" onclick="showEducationalCategory('all')">All Resources</button>
                <button class="category-btn" onclick="showEducationalCategory('nutrition')">Nutrition</button>
                <button class="category-btn" onclick="showEducationalCategory('food-science')">Food Science</button>
                <button class="category-btn" onclick="showEducationalCategory('culinary-history')">Culinary History</button>
                <button class="category-btn" onclick="showEducationalCategory('sustainability')">Sustainability</button>
                <button class="category-btn" onclick="showEducationalCategory('food-safety')">Food Safety</button>
            </div>
        </div>
    </section>

    <!-- Nutrition Education Section -->
    <section class="educational-section" id="nutrition-section">
        <div class="section-container">
            <h2><i class="fas fa-apple-alt"></i> Nutrition & Health</h2>
            <p class="section-description">Learn about balanced nutrition and healthy cooking practices</p>
            
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
                <div class="education-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="education-card">
                            <div class="education-image">
                                <img src="uploads/resources/images/<?php echo htmlspecialchars($row['resourcesImage']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['resourceName']); ?>" width="300">
                                <div class="education-overlay">
                                    <?php if (!empty($row['pdfFile'])): ?>
                                        <a href="uploads/resources/pdfs/<?php echo htmlspecialchars($row['pdfFile']); ?>" 
                                           class="download-btn" download>
                                            <i class="fas fa-download"></i> PDF
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($row['Video'])): ?>
                                        <a href="uploads/resources/videos/<?php echo htmlspecialchars($row['Video']); ?>" 
                                           class="download-btn" target="_blank">
                                            <i class="fas fa-play"></i> Video
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="education-content">
                                <h3><?php echo htmlspecialchars($row['resourceName']); ?></h3>
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No culinary resources found yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
  <?php include('footer.php'); ?>

    <script src="script.js"></script>
    <script src="eduRes.js"></script>
</body>
</html>