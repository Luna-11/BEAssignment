<?php
session_start();
include('configMysql.php');
include('functions.php');

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$usersData = $userID ? showUser($userID) : [];
$users = $usersData[0] ?? null;

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'danger';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - FoodFusion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --food-primary: #C89091;
      --food-text: #7b4e48;
      --food-lightest: #fcfaf2;
      --food-light-pink: #e9d0cb;
      --food-light-yellow: #f9f1e5;
      --food-medium-pink: #ddb2b1;
    }
    
    .food-primary-bg {
      background-color: var(--food-primary);
    }
    
    .food-light-pink-bg {
      background-color: var(--food-light-pink);
    }
    
    .food-light-yellow-bg {
      background-color: var(--food-light-yellow);
    }
    
    .food-medium-pink-bg {
      background-color: var(--food-medium-pink);
    }
    
    .food-text {
      color: var(--food-text);
    }
    
    .food-primary-text {
      color: var(--food-primary);
    }
  </style>
</head>
<body class="bg-[#fcfaf2] text-[#7b4e48] font-sans min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="food-primary-bg text-white p-4 shadow-md">
  <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
    <a href="index.html" class="text-2xl font-bold mb-4 md:mb-0">FoodFusion</a>
    <div class="flex flex-wrap justify-center space-x-4 md:space-x-6">
      <a href="index.html" class="hover:underline py-1">Home</a>
      <a href="about.html" class="hover:underline py-1">About Us</a>
      <a href="recipes.html" class="hover:underline py-1">Recipe Collection</a>
      <a href="cookbook.html" class="hover:underline py-1">Community Cookbook</a>
      <a href="resources.html" class="hover:underline py-1">Culinary Resources</a>
      <a href="contact.html" class="font-bold underline py-1">Contact Us</a>
    </div>
    <div class="flex items-center space-x-4 mt-4 md:mt-0">
      <a href="login.html" class="hover:underline">Login</a>
      <a href="register.html" class="bg-white text-[#7b4e48] px-4 py-2 rounded hover:bg-gray-100 font-medium">Sign Up</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="food-light-yellow-bg py-16">
  <div class="container mx-auto px-4 text-center">
    <h1 class="text-4xl md:text-5xl font-bold food-text mb-4">Contact Us</h1>
    <p class="text-xl max-w-2xl mx-auto">We'd love to hear from you! Reach out with enquiries, recipe requests, or feedback.</p>
  </div>
</section>

<!-- Contact Content -->
<section class="py-12">
  <div class="container mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">

      <!-- Contact Info -->
      <div class="food-light-pink-bg rounded-lg p-8">
        <h2 class="text-3xl font-bold food-text mb-6">Get in Touch</h2>
        <p class="text-lg mb-8">Whether you have questions about recipes, want to share feedback, or need cooking advice, we're here to help!</p>

        <div class="space-y-8">
          <!-- Email -->
          <div class="flex items-start">
            <div class="food-primary-text text-2xl mr-4 mt-1">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold food-text mb-2">Email Us</h3>
              <p class="mb-1">hello@foodfusion.com</p>
              <p>support@foodfusion.com</p>
            </div>
          </div>

          <!-- Phone -->
          <div class="flex items-start">
            <div class="food-primary-text text-2xl mr-4 mt-1">
              <i class="fas fa-phone"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold food-text mb-2">Call Us</h3>
              <p class="mb-1">+1 (555) 123-4567</p>
              <p>Mon–Fri, 9AM–6PM EST</p>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="mt-10">
          <h3 class="text-xl font-bold food-text mb-4">Connect With Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="#" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="food-primary-text text-2xl hover:opacity-80">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="food-light-yellow-bg rounded-lg p-8">
        <!-- Success/Error Message -->
        <?php if ($message): ?>
          <div class="p-4 mb-6 rounded <?= $message_type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>

        <form id="contactForm" class="contact-form" method="POST" action="contactSave.php">
          <h2 class="text-3xl font-bold food-text mb-6">Send us a Message</h2>

          <?php if (!$userID): ?>
            <!-- Guest user: must fill name + email -->
            <div class="grid md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="firstName" class="block food-text font-medium mb-2">First Name</label>
                <input type="text" id="firstName" name="firstName" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
              </div>
              <div>
                <label for="lastName" class="block food-text font-medium mb-2">Last Name</label>
                <input type="text" id="lastName" name="lastName" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
              </div>
            </div>

            <div class="mb-4">
              <label for="email" class="block food-text font-medium mb-2">Email Address</label>
              <input type="email" id="email" name="email" required 
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
            </div>
          <?php else: ?>
            <!-- Logged in user: autofill -->
            <?php if ($users): ?>
              <input type="hidden" name="userID" value="<?= htmlspecialchars($users['userID']) ?>">
              <input type="hidden" name="firstName" value="<?= htmlspecialchars($users['FirstName']) ?>">
              <input type="hidden" name="lastName" value="<?= htmlspecialchars($users['LastName']) ?>">
              <input type="hidden" name="email" value="<?= htmlspecialchars($users['email']) ?>">
              <div class="mb-4 p-4 bg-[#e9d0cb] rounded-lg">
                <p class="food-text">
                  <strong>Logged in as:</strong><br>
                  <?= htmlspecialchars($users['FirstName'] . " " . $users['LastName']) ?><br>
                  <?= htmlspecialchars($users['email']) ?>
                </p>
              </div>
            <?php else: ?>
              <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                <p><strong>Error:</strong> User not found in database.</p>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Subject -->
          <div class="mb-4">
            <label for="subject" class="block food-text font-medium mb-2">Subject</label>
            <input type="text" id="subject" name="subject" required placeholder="Enter subject..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent">
          </div>

          <!-- Message -->
          <div class="mb-6">
            <label for="message" class="block food-text font-medium mb-2">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help you..." required 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C89091] focus:border-transparent"></textarea>
          </div>

          <!-- Submit Button -->
          <button type="submit" 
                  class="w-full food-primary-bg text-white py-3 px-6 rounded-lg font-bold text-lg hover:bg-[#b58081] transition duration-300">
            Send Message
          </button>
          
          <p id="formResponse" class="mt-4 text-center"></p>
        </form>
      </div>

    </div>
  </div>
</section>

<!-- Footer -->
<footer class="food-primary-bg text-white py-8 mt-auto">
  <div class="container mx-auto px-4">
    <div class="grid md:grid-cols-4 gap-8">
      <div>
        <h3 class="text-xl font-bold mb-4">FoodFusion</h3>
        <p>Bringing food enthusiasts together through shared culinary experiences.</p>
      </div>
      <div>
        <h3 class="text-xl font-bold mb-4">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="index.html" class="hover:underline">Home</a></li>
          <li><a href="about.html" class="hover:underline">About Us</a></li>
          <li><a href="recipes.html" class="hover:underline">Recipe Collection</a></li>
          <li><a href="cookbook.html" class="hover:underline">Community Cookbook</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-xl font-bold mb-4">Resources</h3>
        <ul class="space-y-2">
          <li><a href="resources.html" class="hover:underline">Culinary Resources</a></li>
          <li><a href="educational.html" class="hover:underline">Educational Resources</a></li>
          <li><a href="privacy.html" class="hover:underline">Privacy Policy</a></li>
          <li><a href="cookies.html" class="hover:underline">Cookie Policy</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-xl font-bold mb-4">Connect With Us</h3>
        <div class="flex space-x-4">
          <a href="#" class="hover:opacity-80">Facebook</a>
          <a href="#" class="hover:opacity-80">Instagram</a>
          <a href="#" class="hover:opacity-80">Twitter</a>
          <a href="#" class="hover:opacity-80">Pinterest</a>
        </div>
      </div>
    </div>
    <div class="border-t border-white border-opacity-30 mt-8 pt-8 text-center">
      <p>&copy; 2024 FoodFusion. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
  // Form submission handling
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simple validation
    const formData = new FormData(this);
    let isValid = true;
    
    // Check required fields
    for (let [key, value] of formData.entries()) {
      if (!value.trim()) {
        isValid = false;
        break;
      }
    }
    
    if (!isValid) {
      document.getElementById('formResponse').textContent = 'Please fill in all required fields.';
      document.getElementById('formResponse').className = 'mt-4 text-center text-red-600';
      return;
    }
    
    // Simulate form submission
    document.getElementById('formResponse').textContent = 'Sending message...';
    document.getElementById('formResponse').className = 'mt-4 text-center text-blue-600';
    
    // In a real implementation, you would send the form data to the server here
    setTimeout(() => {
      document.getElementById('formResponse').textContent = 'Message sent successfully!';
      document.getElementById('formResponse').className = 'mt-4 text-center text-green-600';
      this.reset();
    }, 1500);
  });
</script>

</body>
</html>