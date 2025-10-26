<?php
session_start();
$show_popup = !isset($_SESSION['userID']);

include('configMysql.php');

$sql = "SELECT * FROM foodType";
$result = $conn->query($sql);

$featured_sql = "SELECT r.*, u.first_name, d.difficultyName 
                 FROM recipe r 
                 LEFT JOIN users u ON r.userID = u.id 
                 LEFT JOIN difficultylev d ON r.difficultID = d.difficultyID 
                 ORDER BY r.date DESC 
                 LIMIT 4";
$featured_result = $conn->query($featured_sql);
$featured_recipes = [];
if ($featured_result && $featured_result->num_rows > 0) {
    while ($row = $featured_result->fetch_assoc()) {
        $featured_recipes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodFusion Sharing</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    /* Slideshow Styles */
    .slideshow-container {
      max-width: 1000px;
      position: relative;
      margin: auto;
      overflow: hidden;
    }
    
    .mySlides {
      display: none;
      position: relative;
      width: 100%;
    }
    
    .mySlides.active {
      display: block;
    }
    
    .slide-image {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 10px;
    }
    
    .slide-content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(123, 78, 72, 0.8);
      color: #f9f1e5;
      padding: 15px;
      border-radius: 0 0 10px 10px;
    }
    
    .prev, .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      padding: 16px;
      margin-top: -22px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      transition: 0.3s ease;
      border-radius: 0 3px 3px 0;
      user-select: none;
      background-color: rgba(0,0,0,0.5);
      z-index: 10;
    }
    
    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }
    
    .prev:hover, .next:hover {
      background-color: rgba(0,0,0,0.8);
    }
    
    .dot-container {
      text-align: center;
      padding: 20px;
    }
    
    .dot {
      cursor: pointer;
      height: 15px;
      width: 15px;
      margin: 0 5px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.3s ease;
    }
    
    .active-dot, .dot:hover {
      background-color: #7b4e48;
    }
    
    /* Smooth fade transition */
    .fade {
      animation: fade 1s ease-in-out;
    }
    
    @keyframes fade {
      from { opacity: 0.7; }
      to { opacity: 1; }
    }

    /* Recipe Card Styles */
    .recipe-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .recipe-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .recipe-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .recipe-content {
      padding: 1rem;
    }

    .recipe-title {
      font-size: 1.125rem;
      font-weight: bold;
      color: #7b4e48;
      margin-bottom: 0.5rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .recipe-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.75rem;
      font-size: 0.875rem;
      color: #666;
    }

    .recipe-difficulty {
      padding: 0.25rem 0.5rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .difficulty-easy { background: #e8f5e8; color: #2e7d32; }
    .difficulty-medium { background: #fff3e0; color: #ef6c00; }
    .difficulty-hard { background: #ffebee; color: #c62828; }

    .recipe-description {
      color: #666;
      font-size: 0.875rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 1rem;
    }

    .recipe-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.75rem;
      color: #888;
    }

    /* Popup Styles */
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .hidden {
      display: none;
    }

    /* Category Section Styles */
    .category-grid a {
      transition: all 0.3s ease;
    }

    .category-grid a:hover {
      transform: translateY(-5px);
    }

    .category-grid a:hover .rounded-full {
      background-color: #e9d0cb;
      box-shadow: 0 4px 8px rgba(123, 78, 72, 0.2);
    }
  </style>
</head>
<body class="bg-[#f9f1e5] text-[#7b4e48] min-h-screen flex flex-col">
  
  <?php include 'navbar.php'; ?>

  <!-- Join Us Popup -->
  <?php if ($show_popup): ?>
    <div id="joinUsPopup" class="popup-overlay hidden">
      <?php include 'joinUs.php'; ?>
    </div>
    <script>
      // Show popup after 3 seconds
      window.addEventListener('load', () => {
        setTimeout(() => {
          document.getElementById('joinUsPopup').classList.remove('hidden');
        }, 3000);
      });
    </script>
  <?php endif; ?>

  <!-- Hero Section -->
  <header class="hero bg-gradient-to-r from-[rgba(189,150,180,0.4)] to-[rgba(14,13,14,0.9)] h-64 md:h-96 flex items-center justify-center text-[#e9d0cb] text-center px-4" style="background-image: url('./BEpics/bg1.jpg'); background-size:cover;">
    <div class="hero-text">
      <h1 class="text-3xl md:text-5xl mb-4">Share the food & Share the love</h1>
      <p class="text-base md:text-xl max-w-2xl mx-auto">Connecting the world through the joy of cooking and shared recipes.</p>
    </div>
  </header>

<!-- Category Section -->
<section class="categories text-center py-6">
  <h2 class="text-2xl md:text-3xl mb-6">Explore By Category</h2>
  <div class="category-grid grid grid-cols-4 gap-6 max-w-2xl mx-auto px-2">
    <?php 
    // Re-fetch categories with proper image handling
    $category_sql = "SELECT foodType, imagePath FROM foodType";
    $category_result = $conn->query($category_sql);
    
    if ($category_result && $category_result->num_rows > 0): ?>
      <?php while ($row = $category_result->fetch_assoc()): 
        // Handle image path properly
        $imgPath = 'BEpics/default.png'; // Default fallback
        
        if (!empty($row['imagePath'])) {
          $imagePath = $row['imagePath'];
          
          // Remove any leading slashes or dots for consistency
          $imagePath = ltrim($imagePath, './');
          
          // Check if file exists in common locations
          $possiblePaths = [
            $imagePath,
            'BEpics/' . $imagePath,
            'uploads/' . $imagePath,
            'images/' . $imagePath
          ];
          
          foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
              $imgPath = $path;
              break;
            }
          }
          
          // If no file found, use default
          if ($imgPath === 'BEpics/default.png') {
            error_log("Category image not found: " . $imagePath);
          }
        }
      ?>
        <div class="flex flex-col items-center">
          <a href="re.php?category=<?= urlencode($row['foodType']) ?>" class="flex flex-col items-center group">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gray-100 flex items-center justify-center transition-all duration-300 group-hover:bg-[#e9d0cb] group-hover:shadow-lg">
              <img src="<?= htmlspecialchars($imgPath) ?>" 
                   alt="<?= htmlspecialchars($row['foodType']) ?>" 
                   class="w-8 h-8 md:w-12 md:h-12 object-contain"
                   onerror="this.src='BEpics/default.png'">
            </div>
            <p class="mt-2 text-sm group-hover:text-[#C89091] transition-colors"><?= htmlspecialchars($row['foodType']) ?></p>
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-span-4">
        <p class="text-sm text-gray-500">No categories found.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

  <?php
  $featured_sql = "SELECT 
      r.recipeID, 
      r.recipeName, 
      r.recipeDescription, 
      r.image, 
      r.date,
      r.userID,
      r.difficultID,
      d.difficultyName
  FROM recipe r
  LEFT JOIN difficultylev d ON r.difficultID = d.difficultyID
  ORDER BY r.date DESC 
  LIMIT 4";

  $featured_result = $conn->query($featured_sql);

  $featured_recipes = [];
  if ($featured_result && $featured_result->num_rows > 0) {
      while ($row = $featured_result->fetch_assoc()) {
          $featured_recipes[] = $row;
      }
      echo "<!-- Found " . count($featured_recipes) . " recipes -->";
  } else {
      echo "<!-- No recipes found -->";
  }
  ?>

  <!-- Featured Recipes Section -->
  <section class="featured-recipes py-12">
      <div class="container mx-auto px-4">
          <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Featured Recipes</h2>
          
          <?php if (!empty($featured_recipes)): ?>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                  <?php foreach ($featured_recipes as $recipe): 
                      // Handle recipe image
                      $recipeImage = 'BEpics/default-recipe.jpg';
                      if (!empty($recipe['image'])) {
                          $imageFile = $recipe['image'];
                          $possiblePaths = [
                              $imageFile,
                              'BEpics/' . $imageFile,
                              './BEpics/' . $imageFile,
                              'BEpics/recipes/' . $imageFile
                          ];
                          
                          foreach ($possiblePaths as $path) {
                              if (file_exists($path)) {
                                  $recipeImage = $path;
                                  break;
                              }
                          }
                      }

                      // Format date
                      $formattedDate = 'Recently';
                      if (!empty($recipe['date']) && $recipe['date'] != '0000-00-00 00:00:00') {
                          $formattedDate = date('M j, Y', strtotime($recipe['date']));
                      }

                      // Get difficulty level
                      $difficultyClass = 'difficulty-medium';
                      $difficultyName = $recipe['difficultyName'] ?? 'Medium';
                      if ($difficultyName === 'Easy') $difficultyClass = 'difficulty-easy';
                      if ($difficultyName === 'Hard') $difficultyClass = 'difficulty-hard';
                  ?>
                  
                  <div class="recipe-card">
                      <a href="reDetail.php?id=<?= $recipe['recipeID'] ?>">
                          <img src="<?= htmlspecialchars($recipeImage) ?>" 
                               alt="<?= htmlspecialchars($recipe['recipeName'] ?? 'Recipe Image') ?>" 
                               class="recipe-image"
                               onerror="this.src='BEpics/default-recipe.jpg'">
                      </a>
                      <div class="recipe-content">
                          <h3 class="recipe-title">
                              <a href="reDetail.php?id=<?= $recipe['recipeID'] ?>" class="hover:text-[#C89091] transition-colors">
                                  <?= htmlspecialchars($recipe['recipeName'] ?? 'Untitled Recipe') ?>
                              </a>
                          </h3>
                        
                          <div class="recipe-meta">
                              <span class="recipe-difficulty <?= $difficultyClass ?>">
                                  <?= htmlspecialchars($difficultyName) ?>
                              </span>
                          </div>
                          
                          <?php if (!empty($recipe['recipeDescription'])): ?>
                              <p class="recipe-description">
                                  <?= htmlspecialchars(substr($recipe['recipeDescription'], 0, 100)) . (strlen($recipe['recipeDescription']) > 100 ? '...' : '') ?>
                              </p>
                          <?php endif; ?>
                          
                          <div class="recipe-footer">
                              <span class="recipe-date">
                                  <i class="far fa-clock mr-1"></i>
                                  <?= $formattedDate ?>
                              </span>
                              <a href="reDetail.php?id=<?= $recipe['recipeID'] ?>" class="text-[#C89091] hover:text-[#7b4e48] transition-colors font-medium">
                                  View Recipe →
                              </a>
                          </div>
                      </div>
                  </div>
                  
                  <?php endforeach; ?>
              </div>
          <?php else: ?>
              <div class="text-center py-8">
                  <p class="text-lg text-gray-600 mb-4">No featured recipes found at the moment.</p>
                  <a href="add-recipe.php" class="inline-block mt-4 bg-[#C89091] text-white px-6 py-2 rounded-full hover:bg-[#7b4e48] transition-colors">
                      Share Your Recipe
                  </a>
              </div>
          <?php endif; ?>
      </div>
  </section>

  <!-- Event Slideshow Section-->
  <section class="events-slideshow py-10">
      <h2 class="text-2xl md:text-3xl mb-8 text-center">Upcoming Food Events</h2>
      
      <?php
      // Fetch latest events from database
      $events_sql = "SELECT * FROM event WHERE eventDate >= CURDATE() ORDER BY eventDate ASC LIMIT 5";
      $events_result = $conn->query($events_sql);
      
      echo "<!-- Debug: Query executed -->";
      
      if ($events_result && $events_result->num_rows > 0): 
          $events = [];
          $counter = 0;
          while ($row = $events_result->fetch_assoc()) {
              $events[] = $row;
          }
          
          echo "<!-- Debug: Found " . count($events) . " events -->";
      ?>
      
      <div class="slideshow-container">
          <?php foreach ($events as $index => $event): 
              $counter++;
              
              // Debug output
              echo "<!-- Event #$counter: -->";
              echo "<!-- Title: " . htmlspecialchars($event['title']) . " -->";
              echo "<!-- Image Path: " . htmlspecialchars($event['eventImage']) . " -->";
              echo "<!-- Date: " . htmlspecialchars($event['eventDate']) . " -->";
              
              // Handle image path
              $eventImage = 'BEpics/default-event.jpg';
              
              if (!empty($event['eventImage'])) {
                  $imagePath = $event['eventImage'];
                  echo "<!-- Checking image: $imagePath -->";
                  
                  // Check various possible locations
                  $possiblePaths = [
                      $imagePath,
                      'BEpics/' . $imagePath,
                      './BEpics/' . $imagePath,
                      'BEpics/event1.jpg', // try your existing images
                      'BEpics/event.jpg',
                      'BEpics/banner3.jpg'
                  ];
                  
                  foreach ($possiblePaths as $path) {
                      $fullPath = __DIR__ . '/' . $path;
                      if (file_exists($fullPath)) {
                          $eventImage = $path;
                          echo "<!-- Using image: $path -->";
                          break;
                      }
                  }
              }
              
              $formattedDate = date('F j, Y', strtotime($event['eventDate']));
              $isActive = $counter === 1 ? 'active' : '';
          ?>
          
          <div class="mySlides fade <?= $isActive ?>">
              <img src="<?= htmlspecialchars($eventImage) ?>" 
                   class="slide-image" 
                   alt="<?= htmlspecialchars($event['title']) ?>"
                   onerror="this.src='BEpics/default-event.jpg'; console.log('Image failed to load: <?= htmlspecialchars($eventImage) ?>')">
              <div class="slide-content">
                  <h3 class="text-xl font-bold"><?= htmlspecialchars($event['title']) ?></h3>
                  <p><?= $formattedDate ?> | <?= htmlspecialchars($event['location']) ?></p>
                  <p><?= htmlspecialchars($event['description']) ?></p>
              </div>
          </div>
          
          <?php endforeach; ?>
          
          <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
          <a class="next" onclick="plusSlides(1)">&#10095;</a>
      </div>
      
      <div class="dot-container">
          <?php for ($i = 1; $i <= count($events); $i++): 
              $isActiveDot = $i === 1 ? 'active-dot' : '';
          ?>
              <span class="dot <?= $isActiveDot ?>" onclick="currentSlide(<?= $i ?>)"></span>
          <?php endfor; ?>
      </div>
      
      <?php else: ?>
          <div class="text-center py-8">
              <p class="text-lg mb-4">No upcoming events found.</p>
              <p class="text-sm text-gray-600">Check back later for new food events!</p>
              <?php
              // Show some debug info
              if (!$events_result) {
                  echo "<!-- Query failed: " . $conn->error . " -->";
              } else {
                  echo "<!-- Query successful but no results -->";
              }
              ?>
          </div>
      <?php endif; ?>
  </section>

  <section class="share-hero text-center py-8 relative">
    <h1 class="text-2xl md:text-4xl font-bold mb-3">Share your cooking tips!</h1>
    <p class="text-sm md:text-base mb-6">Let's cook together and communicate with food enthusiasts!</p>
    <div class="relative inline-block">
      <img src="./BEpics/panFlying.png" alt="Pizza" class="w-96 mx-auto">
      <a href="community.php" class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-white text-[#C89091] rounded-full py-4 px-4">Share Yours Now</a>
    </div>
  </section>

  <?php include 'cookies.php'; ?>
  <?php include 'footer.php'; ?>
  
  <script>
    // Slideshow functionality
    let slideIndex = 1;
    let slideInterval;
    
    // Initialize the slideshow
    showSlides(slideIndex);
    startAutoSlide();
    
    function startAutoSlide() {
      // Clear any existing interval
      if (slideInterval) {
        clearInterval(slideInterval);
      }
      // Auto advance slides every 5 seconds
      slideInterval = setInterval(() => {
        plusSlides(1);
      }, 5000);
    }
    
    function plusSlides(n) {
      // Reset the auto slide timer
      startAutoSlide();
      showSlides(slideIndex += n);
    }
    
    function currentSlide(n) {
      // Reset the auto slide timer
      startAutoSlide();
      showSlides(slideIndex = n);
    }
    
    function showSlides(n) {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      let dots = document.getElementsByClassName("dot");
      
      if (n > slides.length) { slideIndex = 1 }
      if (n < 1) { slideIndex = slides.length }
      
      // Hide all slides
      for (i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
        slides[i].classList.remove("fade");
      }
      
      // Remove active class from all dots
      for (i = 0; i < dots.length; i++) {
        dots[i].classList.remove("active-dot");
      }
      
      // Show the current slide and activate the corresponding dot
      if (slides[slideIndex-1]) {
        slides[slideIndex-1].classList.add("active");
        slides[slideIndex-1].classList.add("fade");
        dots[slideIndex-1].classList.add("active-dot");
      }
    }
  </script>
</body>
</html>