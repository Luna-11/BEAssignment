<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cute Cookie Banner - Fixed</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--food-light-yellow);
            color: var(--food-text);
            min-height: 150vh; /* To demonstrate scrolling */
            line-height: 1.6;
        }

        .content {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .cookie-banner {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 600px;
            background: var(--food-lightest);
            color: var(--food-text);
            padding: 1.5rem;
            font-size: 0.95rem;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(123, 78, 72, 0.15);
            border-radius: 16px;
            border: 2px solid var(--food-light-pink);
            
            /* Flexbox layout to align items */
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            
            /* Cute decorative elements */
            background-image: 
                radial-gradient(circle at 10% 10%, var(--food-light-pink) 2px, transparent 2px),
                radial-gradient(circle at 90% 90%, var(--food-light-pink) 2px, transparent 2px);
            background-size: 20px 20px;
            background-repeat: no-repeat;
        }

        .cookie-banner::before {
            content: "🍪";
            position: absolute;
            top: -35px;
            left: 15px;
            font-size: 2.5rem;
            padding: 5px;
            z-index: 1001;
        }

        .cookie-banner p {
            margin: 0;
            flex: 1;
            padding-right: 1rem;
            line-height: 1.5;
        }

        .cookie-banner a {
            color: var(--food-primary);
            font-weight: 600;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .cookie-banner a:hover {
            color: var(--food-text);
        }

        .cookie-banner button {
            background: var(--food-primary);
            color: var(--food-lightest);
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(200, 144, 145, 0.3);
        }

        .cookie-banner button:hover {
            background: var(--food-medium-pink);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(200, 144, 145, 0.4);
        }

        .cookie-banner button:active {
            transform: translateY(0);
        }

        /* Animation for banner appearance */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .cookie-banner.show {
            animation: slideUp 0.5s ease-out forwards;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .cookie-banner {
                flex-direction: column;
                text-align: center;
                padding: 1.2rem;
            }
            
            .cookie-banner p {
                padding-right: 0;
                margin-bottom: 1rem;
            }
            
            .cookie-banner::before {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /* Ensure content has enough bottom padding to avoid overlap */
        .content {
            padding-bottom: 120px;
        }
    </style>
</head>
<body>


    <div id="cookieBanner" class="cookie-banner" style="display: none;">
        <p>
            We use cookies to improve your experience, analyze site usage,  
            and show personalized content and offers. By clicking 'Accept'  
            you agree to our use of cookies. You can manage preferences or  
            read our full <a href="cookies.php">Cookie Policy</a>.
        </p>
        <button id="acceptCookies">Accept</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cookieBanner = document.getElementById('cookieBanner');
            const acceptBtn = document.getElementById('acceptCookies');

            // Check localStorage for previous acceptance
            const lastAccepted = localStorage.getItem('cookiesAccepted');
            const now = new Date().getTime();

            // 3 minutes in milliseconds
            const threeMinutes = 3 * 60 * 1000;

            // Show banner only if not accepted or 3 minutes have passed since last acceptance
            if (!lastAccepted || (now - parseInt(lastAccepted) > threeMinutes)) {
                cookieBanner.style.display = 'flex';
                // Add animation class after a brief delay
                setTimeout(() => {
                    cookieBanner.classList.add('show');
                }, 100);
            } else {
                cookieBanner.style.display = 'none';
            }

            acceptBtn.addEventListener('click', function() {
                cookieBanner.style.display = 'none';
                localStorage.setItem('cookiesAccepted', now.toString());
                console.log('Cookies accepted - will not show again for 3 minutes');
            });
        });
    </script>
</body>
</html>