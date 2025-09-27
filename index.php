<?php
// session_start();
// if (!isset($_SESSION['userID'])) {
//     header("Location: logIn.php");
//     exit;
// }

// if (isset($_SESSION['success_message'])) {
//     $success_message = $_SESSION['success_message'];
//     unset($_SESSION['success_message']); // Clear the message after displaying
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodFusion Sharing</title>
  <!-- Add Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    /* Custom styles that can't be easily handled by Tailwind */
    .share-pizza-base {
      width: 100%;
      max-width: 400px;
      z-index: 1;
    }
    
    .share-cta-btn {
      position: absolute;
      bottom: 30%;
      left: 50%;
      transform: translateX(-50%);
      z-index: 3;
      transition: all 0.3s ease;
    }
    
    .share-cta-btn:hover {
      background: #e9d0cb;
      color: #fff;
    }
    
    .circle-frame img:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
    }
    
    /* Mobile-friendly hover effects */
    .hover-reveal-item {
      position: relative;
      overflow: hidden;
      border-radius: 0.75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .hover-reveal-item .static-image img {
      transition: transform 0.5s ease;
    }
    
    .hover-reveal-item.active .static-image img,
    .hover-reveal-item:hover .static-image img {
      transform: scale(1.05);
    }
    
    .hover-content {
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
      text-align: center;
      padding: 1rem;
    }
    
    .hover-reveal-item.active .hover-content,
    .hover-reveal-item:hover .hover-content {
      opacity: 1;
      visibility: visible;
    }
    
    .hover-reveal-item.active,
    .hover-reveal-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }
    
    /* Mobile-specific styles */
    @media (max-width: 768px) {
      .hover-reveal-item {
        height: 200px;
      }
      
      /* Make hover content always visible on mobile after tap */
      .hover-reveal-item.mobile-active .hover-content {
        opacity: 1;
        visibility: visible;
      }
      
      .hover-reveal-item.mobile-active .static-image {
        display: none;
      }
    }
    
    /* Category section with exactly 2 lines for ALL screen sizes */
    .category-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      justify-items: center;
      max-width: 500px;
      margin: 0 auto;
    }
    
    /* Make category items smaller on mobile to fit 4 in a row */
    @media (max-width: 480px) {
      .category-grid {
        gap: 0.75rem;
        max-width: 400px;
      }
      
      .circle-frame {
        width: 50px;
        height: 50px;
      }
      
      .circle-frame img {
        width: 25px;
        height: 25px;
      }
      
      .category-item p {
        font-size: 0.7rem;
        margin-top: 0.5rem;
      }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
      
      .share-cta-btn {
        bottom: 25%;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
      }
      
      .menu-items {
        gap: 1rem;
      }
      
      .item {
        width: 45%;
        min-width: 140px;
      }
    }
    
    @media (max-width: 480px) {
      .hero h1 {
        font-size: 1.75rem;
      }
      
      .hover-reveal-item {
        height: 180px;
      }
      
      .item {
        width: 100%;
        max-width: 200px;
      }
      
      .footer-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
    }
  </style>
</head>
<body class="bg-[#f9f1e5] text-[#7b4e48] min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <header class="hero bg-gradient-to-r from-[rgba(189,150,180,0.4)] to-[rgba(14,13,14,0.9)] bg-cover bg-center bg-no-repeat h-64 md:h-96 flex items-center justify-center text-[#e9d0cb] text-center px-4" style="background-image: url('./BEpics/bg1.jpg');">
      <div class="hero-text">
        <h1 class="text-3xl md:text-5xl mb-4 text-center">Share the food & Share the love</h1>
        <p class="text-base md:text-xl max-w-2xl mx-auto text-center">Connecting the world through the joy of cooking and shared recipes.</p>
      </div>
    </header>

    <!-- Category section for the recipe -->
    <section class="categories text-center py-6">
      <h2 class="text-2xl md:text-3xl mb-6 text-[#7b4e48]">Explore By Category</h2>
      <div class="category-grid px-2 pb-2">
        <div class="category-item text-center">
          <a href="dessert.html">
            <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
              <img src="./BEpics/strawberry-cake.png" alt="Dessert" class="w-10 h-10 md:w-15 md:h-15">
            </div>
          </a>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Dessert</p>
        </div>

        <div class="category-item text-center">
          <a href="drink.html">
            <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
              <img src="./BEpics/cocktail.png" alt="Drink" class="w-10 h-10 md:w-15 md:h-15">
            </div>
          </a>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Drink</p>
        </div>

        <div class="category-item text-center">
          <a href="snack.html">
            <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
              <img src="./BEpics/snack.png" alt="Snack" class="w-10 h-10 md:w-15 md:h-15">
            </div>
          </a>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Snack</p>
        </div>

        <div class="category-item text-center">
          <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
            <img src="./BEpics/course.png" alt="Main Course" class="w-10 h-10 md:w-15 md:h-15">
          </div>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Main Course</p>
        </div>

        <div class="category-item text-center">
          <a href="Salad.html">
            <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
              <img src="./BEpics/salad.png" alt="Salad" class="w-10 h-10 md:w-15 md:h-15">
            </div>
          </a>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Salad</p>
        </div>

        <div class="category-item text-center">
          <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
            <img src="./BEpics/garlic-bread.png" alt="Starter" class="w-10 h-10 md:w-15 md:h-15">
          </div>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Starter</p>
        </div>

        <div class="category-item text-center">
          <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
            <img src="./BEpics/english-breakfast.png" alt="Breakfast" class="w-10 h-10 md:w-15 md:h-15">
          </div>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Breakfast</p>
        </div>

        <div class="category-item text-center">
          <a href="snack.html">
            <div class="circle-frame w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 border-[#ddb2b1] overflow-hidden mx-auto flex items-center justify-center">
              <img src="./BEpics/hot-soup.png" alt="Soup" class="w-10 h-10 md:w-15 md:h-15">
            </div>
          </a>
          <p class="mt-2 text-xs md:text-sm font-medium text-[#7b4e48]">Soup</p>
        </div>
      </div>
    </section>

    <!-- Featured Recipes Section -->
    <section class="hover-reveal-section py-4 md:py-16 text-center bg-[#f9f1e5] px-4">
      <h1 class="text-2xl md:text-4xl text-[#7b4e48] mb-8 md:mb-10">Dietary preferences options</h1>
      <div class="hover-reveal-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 max-w-6xl mx-auto">
        <div class="hover-reveal-item w-full h-48 md:h-64" data-item="1">
          <div class="static-image w-full h-full">
            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8ZGVsaWNpb3VzJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="Gourmet Pasta" class="w-full h-full object-cover">
          </div>
          <div class="hover-content">
            <h3 class="text-lg md:text-xl font-bold mb-2">Vegan</h3>
            <p class="text-sm md:text-base font-light">Creamy Alfredo pasta with fresh herbs and parmesan cheese</p>
            <a href="#" class="btn mt-3 md:mt-4 inline-block bg-[#C89091] text-white py-1 px-3 md:py-2 md:px-4 rounded-full font-semibold shadow-lg transition-all duration-300 hover:bg-[#ddb2b1] hover:-translate-y-1 text-sm md:text-base">View Recipe</a>
          </div>
        </div>
        
        <div class="hover-reveal-item w-full h-48 md:h-64" data-item="2">
          <div class="static-image w-full h-full">
            <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGRlbGljaW91cyUyMGZvb2R8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" alt="Exotic Dessert" class="w-full h-full object-cover">
          </div>
          <div class="hover-content">
            <h3 class="text-lg md:text-xl font-bold mb-2">Vegetarian</h3>
            <p class="text-sm md:text-base font-light">Chocolate lava cake with vanilla ice cream and berry compote</p>
            <a href="#" class="btn mt-3 md:mt-4 inline-block bg-[#C89091] text-white py-1 px-3 md:py-2 md:px-4 rounded-full font-semibold shadow-lg transition-all duration-300 hover:bg-[#ddb2b1] hover:-translate-y-1 text-sm md:text-base">View Recipe</a>
          </div>
        </div>
        
        <div class="hover-reveal-item w-full h-48 md:h-64" data-item="3">
          <div class="static-image w-full h-full">
            <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGRlbGljaW91cyUyMGZvb2R8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" alt="Healthy Bowl" class="w-full h-full object-cover">
          </div>
          <div class="hover-content">
            <h3 class="text-lg md:text-xl font-bold mb-2">Gluten Free</h3>
            <p class="text-sm md:text-base font-light">Quinoa bowl with roasted vegetables and tahini dressing</p>
            <a href="#" class="btn mt-3 md:mt-4 inline-block bg-[#C89091] text-white py-1 px-3 md:py-2 md:px-4 rounded-full font-semibold shadow-lg transition-all duration-300 hover:bg-[#ddb2b1] hover:-translate-y-1 text-sm md:text-base">View Recipe</a>
          </div>
        </div>
        
        <div class="hover-reveal-item w-full h-48 md:h-64" data-item="4">
          <div class="static-image w-full h-full">
            <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fGRlbGljaW91cyUyMGZvb2R8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" alt="Artisan Bread" class="w-full h-full object-cover">
          </div>
          <div class="hover-content">
            <h3 class="text-lg md:text-xl font-bold mb-2">Dairy-Free</h3>
            <p class="text-sm md:text-base font-light">Homemade sourdough with rosemary and sea salt</p>
            <a href="#" class="btn mt-3 md:mt-4 inline-block bg-[#C89091] text-white py-1 px-3 md:py-2 md:px-4 rounded-full font-semibold shadow-lg transition-all duration-300 hover:bg-[#ddb2b1] hover:-translate-y-1 text-sm md:text-base">View Recipe</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Menu Section -->
    <section class="menu py-10 md:py-16 text-center px-4">
      <h2 class="text-2xl md:text-3xl mb-8 md:mb-10 text-[#7b4e48]">Share your cooking experience with us</h2>
      <div class="menu-items flex justify-center gap-3 md:gap-5 flex-wrap">
        <div class="item p-2 md:p-3 rounded-lg w-40 md:w-48 shadow-sm">
          <img src="./BEpics/cc1.png" alt="Dish 1" class="w-full rounded-md">
          <p class="my-2 md:my-3 font-bold text-[#7b4e48] text-sm md:text-base">팜미엔 볶음밥</p>
          <span class="text-[#555] text-sm md:text-base">5,500원</span>
        </div>
        <div class="item p-2 md:p-3 rounded-lg w-40 md:w-48 shadow-sm">
          <img src="./BEpics/cc2.png" alt="Dish 2" class="w-full rounded-md">
          <p class="my-2 md:my-3 font-bold text-[#7b4e48] text-sm md:text-base">팜미엔 짬뽕</p>
          <span class="text-[#555] text-sm md:text-base">5,500원</span>
        </div>
        <div class="item p-2 md:p-3 rounded-lg w-40 md:w-48 shadow-sm">
          <img src="./BEpics/cc3.png" alt="Dish 3" class="w-full rounded-md">
          <p class="my-2 md:my-3 font-bold text-[#7b4e48] text-sm md:text-base">팜미엔 볶음밥</p>
          <span class="text-[#555] text-sm md:text-base">5,500원</span>
        </div>
        <div class="item p-2 md:p-3 rounded-lg w-40 md:w-48 shadow-sm">
          <img src="./BEpics/cc1.png" alt="Dish 1" class="w-full rounded-md">
          <p class="my-2 md:my-3 font-bold text-[#7b4e48] text-sm md:text-base">팜미엔 볶음밥</p>
          <span class="text-[#555] text-sm md:text-base">5,500원</span>
        </div>
        <div class="item p-2 md:p-3 rounded-lg w-40 md:w-48 shadow-sm">
          <img src="./BEpics/cc1.png" alt="Dish 1" class="w-full rounded-md">
          <p class="my-2 md:my-3 font-bold text-[#7b4e48] text-sm md:text-base">팜미엔 볶음밥</p>
          <span class="text-[#555] text-sm md:text-base">5,500원</span>
        </div>
      </div>
    </section>

    <!-- Community Section -->
    <section class="share-hero text-center py-8 md:py-12 relative px-4">
      <h1 class="text-2xl md:text-4xl font-bold mb-3">Build Your Dream Pizza</h1>
      <p class="text-sm md:text-base text-[#444] mb-6 md:mb-10">Choose your crust, toppings, and sauces to make it just the way you love.</p>
      <!-- Pizza base -->
      <div class="share-pizza-container relative inline-block max-w-full">
        <img src="./BEpics/panFlying.png" alt="Pizza" class="share-pizza-base">
        <a href="community.php" class="share-cta-btn bg-white text-[#C89091] rounded-full py-2 px-3 md:py-3 md:px-4 text-decoration-none text-sm md:text-base">Share Yours Now</a>
      </div>

      <!-- Background doodles -->
      <div class="share-background-doodles absolute top-0 left-0 w-full h-full opacity-20 -z-10"></div>
    </section>


    <!-- Cookie Consent -->
    <?php include 'cookies.php'; ?>

    <?php include 'footer.php'; ?>

<script>
  // Mobile-friendly hover functionality
  document.addEventListener('DOMContentLoaded', function() {
    const hoverItems = document.querySelectorAll('.hover-reveal-item');
    let activeItem = null;

    // Check if device is touch-enabled
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (isTouchDevice) {
      // For touch devices, add click functionality
      hoverItems.forEach(item => {
        item.addEventListener('click', function(e) {
          // Prevent triggering if clicking on a link inside the hover content
          if (e.target.tagName === 'A') return;

          // Remove active class from previously active item
          if (activeItem && activeItem !== this) {
            activeItem.classList.remove('mobile-active');
          }

          // Toggle active class on current item
          this.classList.toggle('mobile-active');
          activeItem = this.classList.contains('mobile-active') ? this : null;
        });
      });

      // Close active item when clicking outside
      document.addEventListener('click', function(e) {
        if (
          activeItem &&
          !activeItem.contains(e.target) &&
          !e.target.closest('.hover-reveal-item')
        ) {
          activeItem.classList.remove('mobile-active');
          activeItem = null;
        }
      });
    }
  });
</script>


</body>
</html>