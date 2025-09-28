<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Policies - FoodFusion</title>
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
        
        .table-of-contents {
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
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
            z-index: 1000;
        }
        
        .back-to-top:hover {
            background-color: var(--food-text);
        }
        
        .tab-button {
            padding: 12px 24px;
            background-color: var(--food-light-pink);
            border: none;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .tab-button.active {
            background-color: var(--food-light-yellow);
            color: var(--food-text);
        }
        
        .tab-content {
            display: none;
            padding: 20px;
            background-color: var(--food-light-yellow);
            border-radius: 0 8px 8px 8px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .legal-nav {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation Bar -->
  <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        <!-- Page Header -->
        <section class="text-center mb-8">
            <h1 class="text-4xl font-bold food-text mb-4">Legal Policies</h1>
            <p class="text-xl max-w-3xl mx-auto">Review our policies to understand how we protect your information and govern our services</p>
        </section>

        <!-- Tab Navigation -->
        <div class="legal-nav">
            <button class="tab-button active" onclick="openTab('privacy')">Privacy Policy</button>
            <button class="tab-button" onclick="openTab('terms')">Terms of Service</button>
            <button class="tab-button" onclick="openTab('cookies')">Cookie Policy</button>
        </div>

        <!-- Privacy Policy Tab -->
        <div id="privacy" class="tab-content active">
            <div class="policy-content">
                <!-- Introduction -->
                <section class="food-light-yellow-bg rounded-lg p-6 mb-8">
                    <h2 class="text-3xl font-bold food-text mb-4">Privacy Policy</h2>
                    <p class="text-lg">Last updated: December 1, 2024</p>
                    <p class="mt-4">At FoodFusion, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>
                </section>

                <!-- Table of Contents -->
                <div class="food-light-pink-bg rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Table of Contents</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#privacy-info-collection" class="food-primary-text hover:underline">Information We Collect</a></li>
                        <li><a href="#privacy-info-use" class="food-primary-text hover:underline">How We Use Your Information</a></li>
                        <li><a href="#privacy-info-sharing" class="food-primary-text hover:underline">Sharing Your Information</a></li>
                        <li><a href="#privacy-data-security" class="food-primary-text hover:underline">Data Security</a></li>
                        <li><a href="#privacy-your-rights" class="food-primary-text hover:underline">Your Privacy Rights</a></li>
                        <li><a href="#privacy-children" class="food-primary-text hover:underline">Children's Privacy</a></li>
                        <li><a href="#privacy-changes" class="food-primary-text hover:underline">Changes to This Policy</a></li>
                        <li><a href="#privacy-contact" class="food-primary-text hover:underline">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Policy Content -->
                <div class="policy-section" id="privacy-info-collection">
                    <h2 class="text-3xl font-bold food-text mb-4">1. Information We Collect</h2>
                    <p class="mb-4">We collect information that you provide directly to us, as well as information automatically collected when you use our services.</p>
                    
                    <h3 class="text-xl font-bold mt-6 mb-3">Personal Information</h3>
                    <p class="mb-4">When you create an account, submit recipes, or contact us, we may collect:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>Name and contact information (email address)</li>
                        <li>Account credentials (username and password)</li>
                        <li>Profile information (bio, profile picture, dietary preferences)</li>
                        <li>Content you create (recipes, comments, reviews)</li>
                        <li>Communication preferences</li>
                    </ul>
                    
                    <h3 class="text-xl font-bold mt-6 mb-3">Automatically Collected Information</h3>
                    <p class="mb-4">When you visit our website, we automatically collect:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>Device information (browser type, operating system)</li>
                        <li>IP address and general location data</li>
                        <li>Usage data (pages visited, time spent, features used)</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </div>

                <div class="policy-section" id="privacy-info-use">
                    <h2 class="text-3xl font-bold food-text mb-4">2. How We Use Your Information</h2>
                    <p class="mb-4">We use the information we collect for various purposes, including:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li><strong>Providing Services:</strong> To operate, maintain, and improve our website and services</li>
                        <li><strong>Personalization:</strong> To customize your experience and show relevant content</li>
                        <li><strong>Communication:</strong> To send you updates, newsletters, and respond to inquiries</li>
                        <li><strong>Analytics:</strong> To understand how users interact with our website</li>
                        <li><strong>Security:</strong> To protect against fraudulent or unauthorized activity</li>
                        <li><strong>Legal Compliance:</strong> To comply with applicable laws and regulations</li>
                    </ul>
                </div>

                <div class="policy-section" id="privacy-info-sharing">
                    <h2 class="text-3xl font-bold food-text mb-4">3. Sharing Your Information</h2>
                    <p class="mb-4">We do not sell your personal information to third parties. We may share your information in the following circumstances:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li><strong>With Your Consent:</strong> When you explicitly agree to share information</li>
                        <li><strong>Service Providers:</strong> With trusted partners who help us operate our website</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                        <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                    </ul>
                </div>

                <div class="policy-section" id="privacy-data-security">
                    <h2 class="text-3xl font-bold food-text mb-4">4. Data Security</h2>
                    <p class="mb-4">We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                    <p>However, no method of transmission over the Internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>
                </div>

                <div class="policy-section" id="privacy-your-rights">
                    <h2 class="text-3xl font-bold food-text mb-4">5. Your Privacy Rights</h2>
                    <p class="mb-4">Depending on your location, you may have certain rights regarding your personal information:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li><strong>Access:</strong> You can request access to the personal information we hold about you</li>
                        <li><strong>Correction:</strong> You can request correction of inaccurate or incomplete information</li>
                        <li><strong>Deletion:</strong> You can request deletion of your personal information</li>
                        <li><strong>Restriction:</strong> You can request restriction of processing of your information</li>
                        <li><strong>Objection:</strong> You can object to certain processing activities</li>
                        <li><strong>Data Portability:</strong> You can request a copy of your data in a machine-readable format</li>
                    </ul>
                    <p>To exercise these rights, please contact us using the information provided in the "Contact Us" section.</p>
                </div>

                <div class="policy-section" id="privacy-children">
                    <h2 class="text-3xl font-bold food-text mb-4">6. Children's Privacy</h2>
                    <p class="mb-4">Our services are not directed to individuals under the age of 16. We do not knowingly collect personal information from children under 16.</p>
                    <p>If we become aware that we have collected personal information from a child under 16 without verification of parental consent, we will take steps to remove that information from our servers.</p>
                </div>

                <div class="policy-section" id="privacy-changes">
                    <h2 class="text-3xl font-bold food-text mb-4">7. Changes to This Policy</h2>
                    <p class="mb-4">We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
                    <p>We encourage you to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
                </div>

                <div class="policy-section" id="privacy-contact">
                    <h2 class="text-3xl font-bold food-text mb-4">8. Contact Us</h2>
                    <p class="mb-4">If you have any questions about this Privacy Policy, please contact us:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>By email: privacy@foodfusion.com</li>
                        <li>By mail: FoodFusion Privacy Team, 123 Culinary Street, Foodville, FC 12345</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Terms of Service Tab -->
        <div id="terms" class="tab-content">
            <div class="policy-content">
                <!-- Introduction -->
                <section class="food-light-yellow-bg rounded-lg p-6 mb-8">
                    <h2 class="text-3xl font-bold food-text mb-4">Terms of Service</h2>
                    <p class="text-lg">Last updated: December 1, 2024</p>
                    <p class="mt-4">Welcome to FoodFusion! These Terms of Service govern your use of our website and services. By accessing or using FoodFusion, you agree to be bound by these Terms.</p>
                </section>

                <!-- Table of Contents -->
                <div class="food-light-pink-bg rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Table of Contents</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#terms-agreement" class="food-primary-text hover:underline">Agreement to Terms</a></li>
                        <li><a href="#terms-accounts" class="food-primary-text hover:underline">User Accounts</a></li>
                        <li><a href="#terms-content" class="food-primary-text hover:underline">User-Generated Content</a></li>
                        <li><a href="#terms-prohibited" class="food-primary-text hover:underline">Prohibited Activities</a></li>
                        <li><a href="#terms-intellectual" class="food-primary-text hover:underline">Intellectual Property</a></li>
                        <li><a href="#terms-termination" class="food-primary-text hover:underline">Termination</a></li>
                        <li><a href="#terms-disclaimer" class="food-primary-text hover:underline">Disclaimer of Warranties</a></li>
                        <li><a href="#terms-liability" class="food-primary-text hover:underline">Limitation of Liability</a></li>
                        <li><a href="#terms-changes" class="food-primary-text hover:underline">Changes to Terms</a></li>
                        <li><a href="#terms-contact" class="food-primary-text hover:underline">Contact Information</a></li>
                    </ul>
                </div>

                <!-- Policy Content -->
                <div class="policy-section" id="terms-agreement">
                    <h2 class="text-3xl font-bold food-text mb-4">1. Agreement to Terms</h2>
                    <p class="mb-4">By accessing or using FoodFusion, you confirm that you can form a binding contract with FoodFusion, and you accept these Terms of Service and agree to comply with them.</p>
                    <p>If you do not agree to these Terms, you must not access or use our website or services.</p>
                </div>

                <div class="policy-section" id="terms-accounts">
                    <h2 class="text-3xl font-bold food-text mb-4">2. User Accounts</h2>
                    <p class="mb-4">To access certain features of our website, you may need to create an account. When creating an account, you agree to:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Be responsible for all activities that occur under your account</li>
                    </ul>
                    <p>We reserve the right to suspend or terminate your account if any information provided during registration or thereafter proves to be inaccurate, false, or misleading.</p>
                </div>

                <div class="policy-section" id="terms-content">
                    <h2 class="text-3xl font-bold food-text mb-4">3. User-Generated Content</h2>
                    <p class="mb-4">FoodFusion allows users to post, upload, and share content, including recipes, comments, photos, and reviews ("User Content").</p>
                    
                    <h3 class="text-xl font-bold mt-6 mb-3">Your Responsibilities</h3>
                    <p class="mb-4">You are solely responsible for your User Content and the consequences of posting it. By posting User Content, you represent and warrant that:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>You own or have the necessary rights to the User Content</li>
                        <li>The User Content does not violate the privacy, publicity, or intellectual property rights of any third party</li>
                        <li>The User Content is accurate and not misleading</li>
                        <li>The User Content complies with these Terms and all applicable laws</li>
                    </ul>
                    
                    <h3 class="text-xl font-bold mt-6 mb-3">License to FoodFusion</h3>
                    <p class="mb-4">By posting User Content on FoodFusion, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, modify, adapt, publish, translate, and distribute your User Content in any existing or future media.</p>
                </div>

                <div class="policy-section" id="terms-prohibited">
                    <h2 class="text-3xl font-bold food-text mb-4">4. Prohibited Activities</h2>
                    <p class="mb-4">You agree not to engage in any of the following prohibited activities:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>Violating any applicable laws or regulations</li>
                        <li>Infringing upon the intellectual property rights of others</li>
                        <li>Harassing, abusing, or harming another person</li>
                        <li>Uploading or transmitting viruses or any other malicious code</li>
                        <li>Collecting or tracking the personal information of others</li>
                        <li>Interfering with or disrupting the integrity or performance of FoodFusion</li>
                        <li>Attempting to gain unauthorized access to FoodFusion or its related systems</li>
                        <li>Impersonating any person or entity, or falsely stating your affiliation with a person or entity</li>
                    </ul>
                </div>

                <div class="policy-section" id="terms-intellectual">
                    <h2 class="text-3xl font-bold food-text mb-4">5. Intellectual Property</h2>
                    <p class="mb-4">The FoodFusion website and its original content, features, and functionality are owned by FoodFusion and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.</p>
                    <p>Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of FoodFusion.</p>
                </div>

                <div class="policy-section" id="terms-termination">
                    <h2 class="text-3xl font-bold food-text mb-4">6. Termination</h2>
                    <p class="mb-4">We may terminate or suspend your account and bar access to FoodFusion immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever, including but not limited to a breach of the Terms.</p>
                    <p>Upon termination, your right to use FoodFusion will immediately cease. If you wish to terminate your account, you may simply discontinue using FoodFusion or delete your account through your account settings.</p>
                </div>

                <div class="policy-section" id="terms-disclaimer">
                    <h2 class="text-3xl font-bold food-text mb-4">7. Disclaimer of Warranties</h2>
                    <p class="mb-4">Your use of FoodFusion is at your sole risk. The service is provided on an "AS IS" and "AS AVAILABLE" basis. FoodFusion disclaims all warranties of any kind, whether express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>
                    <p>FoodFusion does not warrant that the service will be uninterrupted, timely, secure, or error-free; that the results obtained from the use of the service will be accurate or reliable; or that the quality of any products, services, information, or other material purchased or obtained by you through the service will meet your expectations.</p>
                </div>

                <div class="policy-section" id="terms-liability">
                    <h2 class="text-3xl font-bold food-text mb-4">8. Limitation of Liability</h2>
                    <p class="mb-4">In no event shall FoodFusion, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li>Your access to or use of or inability to access or use the service</li>
                        <li>Any conduct or content of any third party on the service</li>
                        <li>Any content obtained from the service</li>
                        <li>Unauthorized access, use, or alteration of your transmissions or content</li>
                    </ul>
                </div>

                <div class="policy-section" id="terms-changes">
                    <h2 class="text-3xl font-bold food-text mb-4">9. Changes to Terms</h2>
                    <p class="mb-4">We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect.</p>
                    <p>By continuing to access or use FoodFusion after those revisions become effective, you agree to be bound by the revised terms.</p>
                </div>

                <div class="policy-section" id="terms-contact">
                    <h2 class="text-3xl font-bold food-text mb-4">10. Contact Information</h2>
                    <p class="mb-4">If you have any questions about these Terms of Service, please contact us:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>By email: legal@foodfusion.com</li>
                        <li>By mail: FoodFusion Legal Department, 123 Culinary Street, Foodville, FC 12345</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cookie Policy Tab -->
        <div id="cookies" class="tab-content">
            <div class="policy-content">
                <!-- Your existing Cookie Policy content would go here -->
                <section class="food-light-yellow-bg rounded-lg p-6 mb-8">
                    <h2 class="text-3xl font-bold food-text mb-4">Cookie Policy</h2>
                    <p class="text-lg">Last updated: December 1, 2024</p>
                    <p class="mt-4">At FoodFusion, we use cookies and similar technologies to enhance your experience on our website. This Cookie Policy explains what cookies are, how we use them, and your choices regarding their use.</p>
                </section>

                <!-- Table of Contents -->
                <div class="food-light-pink-bg rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Table of Contents</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><a href="#cookies-what" class="food-primary-text hover:underline">What Are Cookies?</a></li>
                        <li><a href="#cookies-use" class="food-primary-text hover:underline">How We Use Cookies</a></li>
                        <li><a href="#cookies-types" class="food-primary-text hover:underline">Types of Cookies We Use</a></li>
                        <li><a href="#cookies-third-party" class="food-primary-text hover:underline">Third-Party Cookies</a></li>
                        <li><a href="#cookies-managing" class="food-primary-text hover:underline">Managing Your Cookie Preferences</a></li>
                        <li><a href="#cookies-updates" class="food-primary-text hover:underline">Updates to This Policy</a></li>
                        <li><a href="#cookies-contact" class="food-primary-text hover:underline">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Policy Content -->
                <div class="policy-section" id="cookies-what">
                    <h2 class="text-3xl font-bold food-text mb-4">1. What Are Cookies?</h2>
                    <p class="mb-4">Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to the website owners.</p>
                    <p>Cookies can be "persistent" or "session" cookies. Persistent cookies remain on your personal computer or mobile device when you go offline, while session cookies are deleted as soon as you close your web browser.</p>
                </div>

                <div class="policy-section" id="cookies-use">
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

                <div class="policy-section" id="cookies-types">
                    <h2 class="text-3xl font-bold food-text mb-4">3. Types of Cookies We Use</h2>
                    <p class="mb-4">We use different types of cookies on our website:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li><strong>Essential Cookies:</strong> Necessary for the website to function properly</li>
                        <li><strong>Performance Cookies:</strong> Help us understand how visitors interact with our website</li>
                        <li><strong>Functionality Cookies:</strong> Enable enhanced functionality and personalization</li>
                        <li><strong>Targeting Cookies:</strong> Used to deliver relevant advertisements</li>
                    </ul>
                </div>

                <div class="policy-section" id="cookies-third-party">
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

                <div class="policy-section" id="cookies-managing">
                    <h2 class="text-3xl font-bold food-text mb-4">5. Managing Your Cookie Preferences</h2>
                    <p class="mb-4">You can control and/or delete cookies as you wish. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed.</p>
                    <p class="mb-4">However, if you do this, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.</p>
                    
                    <div class="food-light-pink-bg rounded-lg p-6 mb-4">
                        <h3 class="text-xl font-bold mb-2">Browser Controls</h3>
                        <p>Most web browsers allow you to control cookies through their settings preferences. However, limiting the ability of websites to set cookies may worsen your overall user experience.</p>
                    </div>
                </div>

                <div class="policy-section" id="cookies-updates">
                    <h2 class="text-3xl font-bold food-text mb-4">6. Updates to This Policy</h2>
                    <p>We may update this Cookie Policy from time to time to reflect changes in technology, legislation, or our operations. We will notify you of any significant changes by posting a prominent notice on our website or by sending you a direct notification.</p>
                </div>

                <div class="policy-section" id="cookies-contact">
                    <h2 class="text-3xl font-bold food-text mb-4">7. Contact Us</h2>
                    <p class="mb-4">If you have any questions about this Cookie Policy, please contact us:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>By email: privacy@foodfusion.com</li>
                        <li>By mail: FoodFusion Privacy Team, 123 Culinary Street, Foodville, FC 12345</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
   <?php include 'footer.php'; ?>

    <!-- Back to Top Button -->
    <button onclick="topFunction()" id="backToTop" class="back-to-top" title="Go to top">↑</button>

    <script>
        // Tab functionality
        function openTab(tabName) {
            // Hide all tab content
            var tabcontent = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            
            // Remove active class from all tab buttons
            var tabbuttons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < tabbuttons.length; i++) {
                tabbuttons[i].classList.remove("active");
            }
            
            // Show the specific tab content and mark button as active
            document.getElementById(tabName).classList.add("active");
            event.currentTarget.classList.add("active");
            
            // Scroll to top of the page
            window.scrollTo(0, 0);
        }

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