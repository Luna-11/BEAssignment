<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-tab {
            background-color: #f3f4f6;
            color: #3b82f6;
            border-left: 4px solid #3b82f6;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
        <?php include 'navbar.php'; ?>
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white h-screen shadow-md fixed">

            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Main</h2>
                </div>
                <ul>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-tab="profile">
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-tab="posts">
                            <span>My Posts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-tab="recipes">
                            <span>Saved Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-tab="saved-posts">
                            <span>Saved Posts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" data-tab="liked-posts">
                            <span>Liked Posts</span>
                        </a>
                    </li>
                </ul>
                
                <div class="px-6 py-2 mt-6">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Security</h2>
                </div>
                <ul>
                    <li>
                        <a href="#" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200" id="logout-btn">
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="ml-64 flex-1 p-8">
            <div id="tab-content">
                <!-- Profile Tab -->
                <div id="profile" class="tab-content active">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h2>
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div class="pt-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- My Posts Tab -->
                <div id="posts" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">My Posts</h2>
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">My First Recipe Post</h3>
                                <p class="text-sm text-gray-500 mt-1">Posted on September 20, 2025</p>
                                <p class="mt-2 text-gray-600">This is a description of my first recipe post. It was a delicious pasta dish that I created last weekend.</p>
                                <div class="mt-2 flex space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Published
                                    </span>
                                </div>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">Quick Breakfast Ideas</h3>
                                <p class="text-sm text-gray-500 mt-1">Posted on September 18, 2025</p>
                                <p class="mt-2 text-gray-600">A collection of my favorite quick and healthy breakfast recipes for busy mornings.</p>
                                <div class="mt-2 flex space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Draft
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Recipes Tab -->
                <div id="recipes" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Saved Recipes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="h-48 bg-gray-200"></div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">Vegetable Stir Fry</h3>
                                    <p class="mt-1 text-sm text-gray-500">By Chef Maria • 30 min</p>
                                    <p class="mt-2 text-gray-600">A quick and healthy vegetable stir fry with a savory sauce.</p>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="h-48 bg-gray-200"></div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">Chocolate Chip Cookies</h3>
                                    <p class="mt-1 text-sm text-gray-500">By Baker John • 45 min</p>
                                    <p class="mt-2 text-gray-600">Classic chocolate chip cookies with a soft center and crispy edges.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Posts Tab -->
                <div id="saved-posts" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Saved Posts</h2>
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">10 Tips for Better Meal Planning</h3>
                                <p class="text-sm text-gray-500 mt-1">By Nutrition Expert • September 15, 2025</p>
                                <p class="mt-2 text-gray-600">Learn how to plan your meals effectively to save time and eat healthier.</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">The Science of Sourdough</h3>
                                <p class="text-sm text-gray-500 mt-1">By Bread Master • September 10, 2025</p>
                                <p class="mt-2 text-gray-600">Understanding the fermentation process that makes sourdough bread so special.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liked Posts Tab -->
                <div id="liked-posts" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Liked Posts</h2>
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">Seasonal Fall Recipes</h3>
                                <p class="text-sm text-gray-500 mt-1">By Seasonal Chef • September 22, 2025</p>
                                <p class="mt-2 text-gray-600">Delicious recipes using autumn's best produce like pumpkins, apples, and squash.</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="text-lg font-medium text-gray-900">Kitchen Organization Hacks</h3>
                                <p class="text-sm text-gray-500 mt-1">By Organization Pro • September 18, 2025</p>
                                <p class="mt-2 text-gray-600">Simple tips to organize your kitchen for maximum efficiency and enjoyment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all sidebar links
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            
            // Add click event listeners to sidebar links
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the target tab from data attribute
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all links
                    sidebarLinks.forEach(l => {
                        l.classList.remove('active-tab');
                    });
                    
                    // Add active class to clicked link
                    this.classList.add('active-tab');
                    
                    // Hide all tab content
                    const tabContents = document.querySelectorAll('.tab-content');
                    tabContents.forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Show the target tab content
                    const targetContent = document.getElementById(targetTab);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
            
            // Set the first tab as active by default
            if (sidebarLinks.length > 0) {
                sidebarLinks[0].classList.add('active-tab');
            }
            
            // Logout button functionality
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        // In a real application, this would redirect to a logout endpoint
                        alert('Logging out...');
                        // window.location.href = '/logout.php';
                    }
                });
            }
        });
    </script>
</body>
</html>