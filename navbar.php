<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion - Fixed Navigation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #C89091;
            --text-color: #7b4e48;
            --lightest-color: #fcfaf2;
            --light_pink: #e9d0cb;
            --medium_pink: #ddb2b1;
            --light_yellow: #f9f1e5;
            --white: #fff;
            --black: #222;
            --light-gray: #bbb;
            --medium-gray: #555;
            --shadow-color: rgba(0,0,0,0.1);
            --border-color: #ccc;
            --button-color: #333;
        }


        body {
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
            background-color: var(--light_yellow);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== NAVIGATION BAR ===== */
        .navbar {
            background-color: var(--primary-color);
            padding: 0.6rem 0;
            box-shadow: 0 2px 5px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .nav-container {
            width: 100%; /* Changed from 90% to remove padding */
            max-width: 1800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            width: 96%;
        }

        .navbar .logo {
            color: var(--white);
            font-size: 1.5rem;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            z-index: 1001;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            flex: 1;
            justify-content: center;
        }

        /* Updated Navigation Bar Styles */
        .nav-links li a {
            color: var(--white);
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
        }

        .nav-icon-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            padding: 0 0.5rem;
        }

        .nav-icon-text i:first-child {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .nav-icon-text span {
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Dropdown specific styles */
        .dropdown {
            position: relative;
        }

        .dropdown .nav-icon-text {
            padding-right: 1.3rem;
        }

        .dropdown-indicator {
            position: absolute;
            right: 0;
            bottom: 6px;
            font-size: 0.9rem;
        }

        /* Dropdown menu styles */
        .nav-links .dropdown-menu {
            display: none;
            position: absolute;
            background-color: var(--light_yellow);
            box-shadow: 0 4px 8px var(--shadow-color);
            list-style: none;
            padding: 10px 0;
            margin: 0;
            top: 100%;
            left: 0;
            z-index: 999;
            min-width: 180px;
            border-radius: 8px;
        }

        .nav-links .dropdown-menu li a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--black);
            text-decoration: none;
            white-space: nowrap;
            text-align: left;
            transition: background-color 0.2s;
        }

        .nav-links .dropdown-menu li a:hover {
            background-color: var(--light_pink);
        }

        .nav-links .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Hover effects */
        .nav-links li a:hover {
            opacity: 0.9;
        }

        .nav-links li a:hover .nav-icon-text i:first-child {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        /* Updated Right Side Icons - Hide on mobile */
        .nav-icons {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-icons a {
            color: var(--white);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 0;
        }

        /* Upload dropdown in nav-icons */
        .nav-icons .dropdown {
            position: relative;
        }

        .nav-icons .dropdown .nav-icon-text {
            padding-right: 1.3rem;
        }

        .nav-icons .dropdown-menu {
            display: none;
            position: absolute;
            background-color: var(--light_yellow);
            box-shadow: 0 4px 8px var(--shadow-color);
            list-style: none;
            padding: 10px 0;
            margin: 0;
            top: 100%;
            right: 0;
            z-index: 999;
            min-width: 180px;
            border-radius: 8px;
        }

        .nav-icons .dropdown-menu li a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--black);
            text-decoration: none;
            white-space: nowrap;
            text-align: left;
            transition: background-color 0.2s;
        }

        .nav-icons .dropdown-menu li a:hover {
            background-color: var(--light_pink);
        }

        .nav-icons .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Hover effects to match main nav */
        .nav-icons a:hover {
            opacity: 0.9;
        }

        .nav-icons a:hover i {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        /* Hamburger Menu - Positioned to the right */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 30px;
            height: 25px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .hamburger span {
            width: 100%;
            height: 3px;
            background-color: var(--white);
            border-radius: 5px;
            transition: all 0.3s linear;
            position: relative;
            transform-origin: center;
        }

        /* Close icon when menu is active */
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Overlay for mobile when menu is open */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .overlay.active {
            display: block;
        }

        /* Mobile-only items - Hidden by default */
        .mobile-only {
            display: none;
        }

        /* Responsive Styles for Navbar */
        @media (max-width: 1024px) {
            .nav-links {
                gap: 1rem;
            }
            
            .nav-icon-text span {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 900px) {
            .hamburger {
                display: flex;
            }
            
            .nav-icons {
                display: none; /* Hide the right side icons on mobile */
            }
            
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%; /* Changed from left to right */
                width: 70%;
                height: 100vh;
                background-color: var(--primary-color);
                flex-direction: column;
                justify-content: flex-start;
                padding-top: 80px;
                transition: right 0.3s ease; /* Changed from left to right */
                z-index: 1000;
                box-shadow: -2px 0 10px rgba(0,0,0,0.1); /* Shadow on left side */
                gap: 0;
            }
            
            .nav-links.active {
                right: 0; /* Changed from left to right */
            }
            
            .nav-links li {
                width: 100%;
                margin: 0;
            }
            
            .nav-links li a {
                padding: 15px 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .nav-icon-text {
                flex-direction: row;
                justify-content: flex-start;
                gap: 15px;
            }
            
            .nav-icon-text i:first-child {
                margin-bottom: 0;
                font-size: 1.3rem;
                width: 25px;
            }
            
            .nav-icon-text span {
                font-size: 1rem;
            }
            
            .dropdown .nav-icon-text {
                padding-right: 0;
            }
            
            .dropdown-indicator {
                position: static;
                margin-left: auto;
                transition: transform 0.3s ease;
            }
            
            .dropdown.active .dropdown-indicator {
                transform: rotate(180deg);
            }
            
            /* FIXED DROPDOWN POSITIONING FOR MOBILE */
            .nav-links .dropdown-menu {
                display: none;
                position: static;
                width: 100%;
                box-shadow: none;
                background-color: rgba(0, 0, 0, 0.1);
                border-radius: 0;
                margin-top: 0;
                padding: 0;
                transform: none;
                left: auto;
            }
            
            .nav-links .dropdown-menu.active {
                display: block;
            }
            
            .nav-links .dropdown-menu li a {
                padding-left: 60px;
                color: var(--white);
                border-bottom: none;
                background-color: rgba(0, 0, 0, 0.1);
            }
            
            /* Mobile upload dropdown */
            .mobile-upload-dropdown .dropdown-menu {
                display: none;
                position: static;
                width: 100%;
                box-shadow: none;
                background-color: rgba(0, 0, 0, 0.15);
                border-radius: 0;
                margin-top: 0;
                padding: 0;
            }
            
            .mobile-upload-dropdown .dropdown-menu.active {
                display: block;
            }
            
            .mobile-upload-dropdown .dropdown-menu li a {
                padding-left: 75px;
                color: var(--white);
                border-bottom: none;
            }
            
            /* Show mobile-only items on mobile */
            .mobile-only {
                display: block;
            }
        }

        @media (max-width: 600px) {
            .nav-container {
                padding: 0 0.5rem; /* Reduced padding on mobile */
            }
            
            .logo {
                font-size: 1.3rem;
            }
            
            .nav-links {
                width: 80%;
            }
        }

        .content-section {
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 1200px;
            width: 90%;
        }
        
        /* Debug styling to show the issue was fixed */
        .debug-info {
            background: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 0.9rem;
            border-left: 4px solid #4CAF50;
        }
        
        .debug-info strong {
            color: #4CAF50;
        }
    </style>
</head>
<body>
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="logo">FoodFusion</a>

                <!-- Centered Navigation Links - Hidden on mobile -->
                <ul class="nav-links" id="navLinks">
                    <li>
                        <a href="re.php">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-utensils"></i>
                                <span>Recipes</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="community.php">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-people-group"></i>
                                <span>Community</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="event.php">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Events</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="aboutUs.php">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>About Us</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="contactUs.html">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-envelope"></i>
                                <span>Contact us</span>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown" id="resourcesDropdown">
                        <a href="#">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-book"></i>
                                <span>Resources</span>
                                <i class="fa-solid fa-caret-down dropdown-indicator"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="eduRes.html">Educational Resources</a></li>
                            <li><a href="culRes.html">Culinary Resources</a></li>
                        </ul>
                    </li>
                    
                    <!-- Mobile-only items (Log Out and Upload) -->
                    <li class="mobile-only">
                        <a href="logIn.php">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                <span>Log Out</span>
                            </div>
                        </a>
                    </li>
                    <li class="mobile-only mobile-upload-dropdown" id="mobileUploadDropdown">
                        <a href="#">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-plus"></i>
                                <span>Upload</span>
                                <i class="fa-solid fa-caret-down dropdown-indicator"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="recipe_upload.html">Recipe Upload</a></li>
                            <li><a href="event_upload.html">Event Upload</a></li>
                            <li><a href="community_upload.html">Community Post Upload</a></li>
                            <li><a href="resource_upload.html">Resource Upload</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Right Side Icons - Visible on desktop only -->
                <div class="nav-icons">
                    <a href="logIn.php" class="nav-icon-text">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Log Out</span>
                    </a>
                    <div class="dropdown" id="desktopUploadDropdown">
                        <a href="#">
                            <div class="nav-icon-text">
                                <i class="fa-solid fa-plus"></i>
                                <span>Upload</span>
                                <i class="fa-solid fa-caret-down dropdown-indicator"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="recipe_upload.html">Recipe Upload</a></li>
                            <li><a href="addEvent.php">Event Upload</a></li>
                            <li><a href="community_upload.html">Community Post Upload</a></li>
                            <li><a href="resource_upload.html">Resource Upload</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Hamburger Menu - Positioned to the right -->
                <button class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>

        <!-- Overlay for mobile menu -->
        <div class="overlay" id="overlay"></div>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    const overlay = document.getElementById('overlay');
    
    // Get ALL dropdowns including the mobile upload dropdown
    const dropdowns = document.querySelectorAll('.dropdown, .mobile-upload-dropdown');

    // Toggle mobile menu
    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
        closeMobileMenu();
    });

    // Handle ALL dropdowns on mobile (both Resources and Upload)
    dropdowns.forEach(dd => {
        dd.addEventListener('click', function(e) {
            if (window.innerWidth <= 900) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle this dropdown
                this.classList.toggle('active');
                const menu = this.querySelector('.dropdown-menu');
                if (menu) menu.classList.toggle('active');
                
                // Close other dropdowns
                dropdowns.forEach(otherDd => {
                    if (otherDd !== this) {
                        otherDd.classList.remove('active');
                        const otherMenu = otherDd.querySelector('.dropdown-menu');
                        if (otherMenu) otherMenu.classList.remove('active');
                    }
                });
            }
        });
    });

    // Close dropdowns when clicking outside (desktop only)
    document.addEventListener('click', function(e) {
        if (window.innerWidth > 900) {
            dropdowns.forEach(dd => {
                if (!dd.contains(e.target)) {
                    const menu = dd.querySelector('.dropdown-menu');
                    if (menu) menu.style.display = 'none';
                }
            });
        }
    });

    // Close menu when clicking on regular nav links (non-dropdown)
    const regularNavLinks = document.querySelectorAll('.nav-links > li:not(.dropdown):not(.mobile-upload-dropdown) > a');
    regularNavLinks.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 900) {
                closeMobileMenu();
            }
        });
    });

    // Close menu when clicking on dropdown menu items
    const dropdownMenuItems = document.querySelectorAll('.dropdown-menu a');
    dropdownMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 900) {
                closeMobileMenu();
            }
        });
    });

    // Desktop hover effect
    dropdowns.forEach(dd => {
        if (window.innerWidth > 900) {
            dd.addEventListener('mouseenter', function() {
                const menu = dd.querySelector('.dropdown-menu');
                if (menu) menu.style.display = 'block';
            });
            dd.addEventListener('mouseleave', function() {
                const menu = dd.querySelector('.dropdown-menu');
                if (menu) menu.style.display = 'none';
            });
        }
    });

    // Function to close mobile menu
    function closeMobileMenu() {
        hamburger.classList.remove('active');
        navLinks.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        
        // Close all dropdowns
        dropdowns.forEach(dd => {
            dd.classList.remove('active');
            const menu = dd.querySelector('.dropdown-menu');
            if (menu) menu.classList.remove('active');
        });
    }
});

    </script>
</body>
</html>