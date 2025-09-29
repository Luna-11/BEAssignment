<?php
session_start();
$show_popup = !isset($_SESSION['userID']); // true if user not logged in

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // clear after displaying
}

include('configMysql.php');

// Fetch categories regardless of success_message
$sql = "SELECT * FROM foodType";
$result = $conn->query($sql);

// Fetch featured recipes (4 most recent by date)
$featured_sql = "SELECT r.*, u.username, d.difficultLevel 
                 FROM recipe r 
                 LEFT JOIN user u ON r.userID = u.userID 
                 LEFT JOIN difficult d ON r.difficultID = d.difficultID 
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
  </style>
</head>
<body class="bg-[#f9f1e5] text-[#7b4e48] min-h-screen flex flex-col">
  
  <?php include 'navbar.php'; ?>

  
  <!-- show success message if exists -->
  <?php if (!empty($success_message)): ?>
    <div class="max-w-2xl mx-auto mt-4 px-4">
      <div class="bg-green-100 text-green-800 p-3 rounded">
        <?= htmlspecialchars($success_message) ?>
      </div>
    </div>
  <?php endif; ?>

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
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): 
            // choose DB path or default
            $imgPath = !empty($row['imagePath']) ? $row['imagePath'] : 'BEpics/default.png';
            // build server path correctly for file_exists
            $serverPath = (strpos($imgPath, '/') === 0) ? __DIR__ . $imgPath : __DIR__ . '/' . $imgPath;
            if (!file_exists($serverPath)) {
                $imgPath = 'BEpics/default.png';
            }
        ?>
          <div class="flex flex-col items-center">
            <a href="category.php?id=<?= (int)$row['foodTypeID'] ?>" class="flex flex-col items-center">
              <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gray-100 flex items-center justify-center">
                <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($row['foodType']) ?>" class="w-8 md:w-12">
              </div>
              <p class="mt-2"><?= htmlspecialchars($row['foodType']) ?></p>
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-sm text-gray-500">No categories found.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Featured Recipes Section -->
  <section class="featured-recipes py-12 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Featured Recipes</h2>
      
      <?php if (!empty($featured_recipes)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <?php foreach ($featured_recipes as $recipe): 
            // Handle recipe image
            $recipeImage = 'BEpics/default-recipe.jpg';
            if (!empty($recipe['image'])) {
                $imagePath = $recipe['image'];
                $possiblePaths = [
                    $imagePath,
                    'BEpics/' . $imagePath,
                    './BEpics/' . $imagePath,
                    'BEpics/recipe-default.jpg'
                ];
                
                foreach ($possiblePaths as $path) {
                    $fullPath = __DIR__ . '/' . $path;
                    if (file_exists($fullPath)) {
                        $recipeImage = $path;
                        break;
                    }
                }
            }

            // Handle difficulty styling
            $difficultyClass = 'difficulty-medium';
            $difficultyText = $recipe['difficultLevel'] ?? 'Medium';
            if (isset($recipe['difficultLevel'])) {
                $difficultyLower = strtolower($recipe['difficultLevel']);
                if (strpos($difficultyLower, 'easy') !== false) {
                    $difficultyClass = 'difficulty-easy';
                } elseif (strpos($difficultyLower, 'hard') !== false || strpos($difficultyLower, 'expert') !== false) {
                    $difficultyClass = 'difficulty-hard';
                }
            }

            // Format date
            $formattedDate = $recipe['date'] ? date('M j, Y', strtotime($recipe['date'])) : 'Recently';
          ?>
          
          <div class="recipe-card">
            <a href="recipe-detail.php?id=<?= $recipe['recipeID'] ?>">
              <img src="<?= htmlspecialchars($recipeImage) ?>" 
                   alt="<?= htmlspecialchars($recipe['recipeName']) ?>" 
                   class="recipe-image"
                   onerror="this.src='BEpics/default-recipe.jpg'">
            </a>
            <div class="recipe-content">
              <h3 class="recipe-title">
                <a href="recipe-detail.php?id=<?= $recipe['recipeID'] ?>" class="hover:text-[#C89091] transition-colors">
                  <?= htmlspecialchars($recipe['recipeName']) ?>
                </a>
              </h3>
              
              <div class="recipe-meta">
                <span class="recipe-difficulty <?= $difficultyClass ?>">
                  <?= htmlspecialchars($difficultyText) ?>
                </span>
                <span class="recipe-author">
                  By <?= htmlspecialchars($recipe['username'] ?? 'Anonymous') ?>
                </span>
              </div>
              
              <?php if (!empty($recipe['recipeDescription'])): ?>
                <p class="recipe-description">
                  <?= htmlspecialchars($recipe['recipeDescription']) ?>
                </p>
              <?php endif; ?>
              
              <div class="recipe-footer">
                <span class="recipe-date">
                  <i class="far fa-clock mr-1"></i>
                  <?= $formattedDate ?>
                </span>
                <a href="recipe-detail.php?id=<?= $recipe['recipeID'] ?>" class="text-[#C89091] hover:text-[#7b4e48] transition-colors font-medium">
                  View Recipe →
                </a>
              </div>
            </div>
          </div>
          
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-8">
          <p class="text-lg text-gray-600">No featured recipes found. Be the first to share your recipe!</p>
          <a href="add-recipe.php" class="inline-block mt-4 bg-[#C89091] text-white px-6 py-2 rounded-full hover:bg-[#7b4e48] transition-colors">
            Share Your Recipe
          </a>
        </div>
      <?php endif; ?>
    </div>
  </section>



<!-- Event Slideshow Section - Debug Version -->
<section class="events-slideshow py-10 bg-[#f3e9dd]">
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

  <!-- Example Other Sections -->
  <section class="menu py-10 text-center">
    <h2 class="text-2xl md:text-3xl mb-8">Share your cooking experience with us</h2>
    <div class="menu-items flex justify-center gap-4 flex-wrap">
      <div class="item w-40"><img src="./BEpics/cc1.png" alt="Dish 1"><p class="font-bold">팜미엔 볶음밥</p><span>5,500원</span></div>
      <div class="item w-40"><img src="./BEpics/cc2.png" alt="Dish 2"><p class="font-bold">팜미엔 짬뽕</p><span>5,500원</span></div>
    </div>
  </section>

  <section class="share-hero text-center py-8 relative">
    <h1 class="text-2xl md:text-4xl font-bold mb-3">Build Your Dream Pizza</h1>
    <p class="text-sm md:text-base mb-6">Choose your crust, toppings, and sauces to make it just the way you love.</p>
    <div class="relative inline-block">
      <img src="./BEpics/panFlying.png" alt="Pizza" class="w-96 mx-auto">
      <a href="community.php" class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-white text-[#C89091] rounded-full py-2 px-4">Share Yours Now</a>
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