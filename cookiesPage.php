<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy - FoodFusion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --food-primary: #C89091;
            --food-text: #7b4e48;
            --food-lightest: #fcfaf2;
            --food-light-pink: #e9d0cb;
            --food-light-yellow: #f9f1e5;
            --food-medium-pink: #ddb2b1;
        }
        
        body {
            color: var(--food-text);
            background-color: var(--food-lightest);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        
        .policy-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .policy-section {
            margin-bottom: 2.5rem;
        }
        
        .cookie-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }
        
        .cookie-table th, .cookie-table td {
            border: 1px solid var(--food-light-pink);
            padding: 12px;
            text-align: left;
        }
        
        .cookie-table th {
            background-color: var(--food-light-yellow);
            font-weight: bold;
        }
        
        .cookie-table tr:nth-child(even) {
            background-color: var(--food-lightest);
        }
        
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--food-primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
        }
        
        .back-to-top:hover {
            background-color: var(--food-text);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="food-primary-bg text-white p-4 shadow-md">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <a href="index.html" class="text-2xl font-bold mb-4 md:mb-0">FoodFusion</a>
            <div class="flex flex-wrap justify-center space-x-4 md:space-x-6">
                <a href="index.html" class="hover:underline py-1">Home</a>
                <a href="about.html" class="hover:underline py-1">About Us</a>
                <a href="recipes.html" class="hover:underline py-1">Recipe Collection</a>
                <a href="cookbook.html" class="hover:underline py-1">Community Cookbook</a>
                <a href="resources.html" class="hover:underline py-1">Culinary Resources</a>
                <a href="contact.html" class="hover:underline py-1">Contact Us</a>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <a href="login.html" class="hover:underline">Login</a>
                <a href="register.html" class="bg-white food-text px-4 py-2 rounded hover:bg-gray-100 font-medium">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Page Header -->
        <section class="text-center mb-8">
            <h1 class="text-4xl font-bold food-text mb-4">Cookie Policy</h1>
            <p class="text-xl max-w-3xl mx-auto">Last updated: December 1, 2024</p>
        </section>

        <!-- Introduction -->
        <section class="policy-content">
            <div class="food-light-yellow-bg rounded-lg p-6 mb-8">
                <p class="text-lg">At FoodFusion, we use cookies and similar technologies to enhance your experience on our website. This Cookie Policy explains what cookies are, how we use them, and your choices regarding their use.</p>
            </div>

            <!-- Table of Contents -->
            <div class="food-light-pink-bg rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4">Table of Contents</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li><a href="#what-are-cookies" class="food-primary-text hover:underline">What Are Cookies?</a></li>
                    <li><a href="#how-we-use-cookies" class="food-primary-text hover:underline">How We Use Cookies</a></li>
                    <li><a href="#types-of-cookies" class="food-primary-text hover:underline">Types of Cookies We Use</a></li>
                    <li><a href="#third-party-cookies" class="food-primary-text hover:underline">Third-Party Cookies</a></li>
                    <li><a href="#managing-cookies" class="food-primary-text hover:underline">Managing Your Cookie Preferences</a></li>
                    <li><a href="#updates" class="food-primary-text hover:underline">Updates to This Policy</a></li>
                    <li><a href="#contact" class="food-primary-text hover:underline">Contact Us</a></li>
                </ul>
            </div>

            <!-- Policy Content -->
            <div class="policy-section" id="what-are-cookies">
                <h2 class="text-3xl font-bold food-text mb-4">1. What Are Cookies?</h2>
                <p class="mb-4">Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to the website owners.</p>
                <p>Cookies can be "persistent" or "session" cookies. Persistent cookies remain on your personal computer or mobile device when you go offline, while session cookies are deleted as soon as you close your web browser.</p>
            </div>

            <div class="policy-section" id="how-we-use-cookies">
                <h2 class="text-3xl font-bold food-text mb-4">2. How We Use Cookies</h2>
                <p class="mb-4">FoodFusion uses cookies for several purposes, including:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Authentication:</strong> To remember your login status and preferences</li>
                    <li><strong>Preferences:</strong> To remember your settings and display preferences</li>
                    <li><strong>Security:</strong> To protect your account and our website</li>
                    <li><strong>Analytics:</strong> To understand how visitors interact with our website</li>
                    <li><strong>Functionality:</strong> To enable features like saving recipes and creating shopping lists</li>
                    <li><strong>Advertising:</strong> To show you relevant culinary content and offers</li>
                </ul>
                <p>We do not use cookies to collect personally identifiable information without your consent.</p>
            </div>

            <div class="policy-section" id="types-of-cookies">
                <h2 class="text-3xl font-bold food-text mb-4">3. Types of Cookies We Use</h2>
                <p class="mb-4">The table below explains the types of cookies we use on FoodFusion and why we use them:</p>
                
                <table class="cookie-table">
                    <thead>
                        <tr>
                            <th>Cookie Type</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Essential Cookies</strong></td>
                            <td>These cookies are necessary for the website to function properly. They enable basic functions like page navigation and access to secure areas of the website.</td>
                            <td>Session or up to 1 year</td>
                        </tr>
                        <tr>
                            <td><strong>Preference Cookies</strong></td>
                            <td>These cookies allow the website to remember choices you make (such as your language preference or region) and provide enhanced, more personal features.</td>
                            <td>Up to 1 year</td>
                        </tr>
                        <tr>
                            <td><strong>Statistical Cookies</strong></td>
                            <td>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</td>
                            <td>Up to 2 years</td>
                        </tr>
                        <tr>
                            <td><strong>Marketing Cookies</strong></td>
                            <td>These cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging to individual users.</td>
                            <td>Up to 1 year</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="policy-section" id="third-party-cookies">
                <h2 class="text-3xl font-bold food-text mb-4">4. Third-Party Cookies</h2>
                <p class="mb-4">In addition to our own cookies, we may also use various third-party cookies to report usage statistics of the website, deliver advertisements on and through the website, and so on.</p>
                <p class="mb-4">These third-party services may include:</p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li><strong>Google Analytics:</strong> To understand how visitors use our website</li>
                    <li><strong>Social Media Platforms:</strong> To enable sharing of recipes and content</li>
                    <li><strong>Advertising Networks:</strong> To display relevant culinary content</li>
                </ul>
                <p>These third-party services have their own privacy policies and cookie policies. We recommend that you review them for more information.</p>
            </div>

            <div class="policy-section" id="managing-cookies">
                <h2 class="text-3xl font-bold food-text mb-4">5. Managing Your Cookie Preferences</h2>
                <p class="mb-4">You can control and/or delete cookies as you wish. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed.</p>
                <p class="mb-4">However, if you do this, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.</p>
                
                <div class="food-light-pink-bg rounded-lg p-6 mb-4">
                    <h3 class="text-xl font-bold mb-2">Browser Controls</h3>
                    <p>Most web browsers allow you to control cookies through their settings preferences. However, limiting the ability of websites to set cookies may worsen your overall user experience.</p>
                </div>
                
                <div class="food-light-yellow-bg rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-2">Cookie Consent Tool</h3>
                    <p>When you first visit FoodFusion, you will see a cookie consent banner that allows you to choose which types of cookies you accept. You can change these preferences at any time by clicking on the "Cookie Settings" link in the footer of our website.</p>
                </div>
            </div>

            <div class="policy-section" id="updates">
                <h2 class="text-3xl font-bold food-text mb-4">6. Updates to This Policy</h2>
                <p>We may update this Cookie Policy from time to time to reflect changes in technology, legislation, or our operations. We will notify you of any significant changes by posting a prominent notice on our website or by sending you a direct notification.</p>
            </div>

            <div class="policy-section" id="contact">
                <h2 class="text-3xl font-bold food-text mb-4">7. Contact Us</h2>
                <p class="mb-4">If you have any questions about our use of cookies or this Cookie Policy, please contact us:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Email:</strong> privacy@foodfusion.com</li>
                    <li><strong>Address:</strong> FoodFusion Privacy Team, 123 Culinary Street, Foodville, FK1 2CD</li>
                    <li><strong>Contact Form:</strong> <a href="contact.html" class="food-primary-text hover:underline">Visit our Contact Page</a></li>
                </ul>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="food-primary-bg text-white py-8 mt-12">
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
                    <h3 class="text-xl font-bold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="privacy.html" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="cookies.html" class="font-bold underline">Cookie Policy</a></li>
                        <li><a href="terms.html" class="hover:underline">Terms of Service</a></li>
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

    <!-- Back to Top Button -->
    <button onclick="topFunction()" id="backToTop" class="back-to-top" title="Go to top">↑</button>

    <script>
        // Back to top button functionality
        window.onscroll = function() {scrollFunction()};
        
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("backToTop").style.display = "block";
            } else {
                document.getElementById("backToTop").style.display = "none";
            }
        }
        
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        
        // Smooth scrolling for table of contents links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>