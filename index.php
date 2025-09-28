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


  <!-- Event Slideshow Section -->
  <section class="events-slideshow py-10 bg-[#f3e9dd]">
    <h2 class="text-2xl md:text-3xl mb-8 text-center">Upcoming Food Events</h2>
    
    <div class="slideshow-container">
      <!-- Slide 1 -->
      <div class="mySlides fade active">
        <img src="./BEpics/event1.jpg" class="slide-image" alt="Food Festival">
        <div class="slide-content">
          <h3 class="text-xl font-bold">International Food Festival</h3>
          <p>June 15-17, 2023 | Central Park</p>
          <p>Join us for a celebration of global cuisine with chefs from around the world.</p>
        </div>
      </div>
      
      <!-- Slide 2 -->
      <div class="mySlides fade">
        <img src="./BEpics/event.jpg" class="slide-image" alt="Cooking Workshop">
        <div class="slide-content">
          <h3 class="text-xl font-bold">Summer Cooking Workshop</h3>
          <p>July 8, 2023 | Culinary Arts Center</p>
          <p>Learn to make refreshing summer dishes with our expert chefs.</p>
        </div>
      </div>
      
      <!-- Slide 3 -->
      <div class="mySlides fade">
        <img src="./BEpics/banner3.jpg" class="slide-image" alt="Farmers Market">
        <div class="slide-content">
          <h3 class="text-xl font-bold">Organic Farmers Market</h3>
          <p>Every Saturday | Downtown Square</p>
          <p>Fresh, locally sourced ingredients from regional farmers.</p>
        </div>
      </div>
      
      <!-- Navigation arrows -->
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    
    <!-- Dots indicator -->
    <div class="dot-container">
      <span class="dot active-dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>
    </div>
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