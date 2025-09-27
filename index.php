<?php
session_start();
$show_popup = !isset($_SESSION['userID']); // true if user not logged in

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // clear after displaying
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
    <div class="category-grid grid grid-cols-4 gap-4 max-w-2xl mx-auto px-2">
      <div><a href="dessert.html"><img src="./BEpics/strawberry-cake.png" alt="Dessert" class="mx-auto w-10 md:w-14"><p class="mt-2">Dessert</p></a></div>
      <div><a href="drink.html"><img src="./BEpics/cocktail.png" alt="Drink" class="mx-auto w-10 md:w-14"><p class="mt-2">Drink</p></a></div>
      <div><a href="snack.html"><img src="./BEpics/snack.png" alt="Snack" class="mx-auto w-10 md:w-14"><p class="mt-2">Snack</p></a></div>
      <div><img src="./BEpics/course.png" alt="Main Course" class="mx-auto w-10 md:w-14"><p class="mt-2">Main Course</p></div>
      <div><a href="Salad.html"><img src="./BEpics/salad.png" alt="Salad" class="mx-auto w-10 md:w-14"><p class="mt-2">Salad</p></a></div>
      <div><img src="./BEpics/garlic-bread.png" alt="Starter" class="mx-auto w-10 md:w-14"><p class="mt-2">Starter</p></div>
      <div><img src="./BEpics/english-breakfast.png" alt="Breakfast" class="mx-auto w-10 md:w-14"><p class="mt-2">Breakfast</p></div>
      <div><a href="snack.html"><img src="./BEpics/hot-soup.png" alt="Soup" class="mx-auto w-10 md:w-14"><p class="mt-2">Soup</p></a></div>
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
</body>
</html>