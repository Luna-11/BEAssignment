<?php

?>

<style>
/* Cookie Consent Styling */
.cookie-consent {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 800px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    padding: 25px;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    border: 2px solid #e9d0cb;
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        transform: translate(-50%, 100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}

.cookie-content {
    flex: 1;
}

.cookie-content p {
    margin: 0;
    color: #7b4e48;
    font-size: 16px;
    line-height: 1.6;
}

.cookie-btn {
    padding: 12px 30px;
    background: #C89091;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(200, 144, 145, 0.3);
    white-space: nowrap;
}

.cookie-btn:hover {
    background: #ddb2b1;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(200, 144, 145, 0.4);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cookie-consent {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .cookie-btn {
        width: 100%;
    }
}
</style>

<div class="cookie-consent" id="cookie-consent">
    <div class="cookie-content">
        <p>We use cookies to enhance your browsing experience and provide personalized content. By continuing to use our site, you agree to our use of cookies.</p>
    </div>
    <button class="cookie-btn" id="accept-cookies">Accept</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cookieConsent = document.getElementById('cookie-consent');
    const acceptCookies = document.getElementById('accept-cookies');
    
    // Check if user has already made a choice
    if (!localStorage.getItem('cookieConsent')) {
        // Show the consent banner after a short delay
        setTimeout(() => {
            cookieConsent.style.display = 'flex';
        }, 1000);
    } else {
        cookieConsent.style.display = 'none';
    }
    
    // Accept cookies
    acceptCookies.addEventListener('click', function() {
        localStorage.setItem('cookieConsent', 'accepted');
        cookieConsent.style.display = 'none';
        
        // Optional: You could trigger your cookie initialization functions here
        console.log('Cookies accepted');
    });
});
</script>