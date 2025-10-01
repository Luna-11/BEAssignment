<?php
session_start();
include('./configMysql.php');
include('./function.php');

// Get events from database
$events = getEvents($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | FoodFusion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Smooth transitions for popup */
        .event-popup {
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .event-popup .popup-content {
            transition: transform 0.3s ease;
            transform: scale(0.9);
        }

        /* Custom scrollbar for modal */
        .popup-content::-webkit-scrollbar {
            width: 8px;
        }

        .popup-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .popup-content::-webkit-scrollbar-thumb {
            background: #C89091;
            border-radius: 10px;
        }

        .popup-content::-webkit-scrollbar-thumb:hover {
            background: #ddb2b1;
        }

        /* Line clamp utility for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure navbar stays below popup */
        .navbar-container {
            z-index: 100;
            position: relative;
        }

        /* Popup overlay - HIGHEST z-index - MATCHING RESOURCE POPUP */
        .popup-overlay {
            z-index: 99999 !important;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#C89091',
                        'text': '#7b4e48',
                        'lightest': '#fcfaf2',
                        'light-pink': '#e9d0cb',
                        'medium-pink': '#ddb2b1',
                        'light-yellow': '#f9f1e5',
                        'medium-gray': '#555',
                    },
                    boxShadow: {
                        'custom': '0 4px 12px rgba(0,0,0,0.1)',
                        'custom-hover': '0 8px 16px rgba(0,0,0,0.1)',
                        'container': '0 -5px 20px rgba(0,0,0,0.1)',
                    }
                }
            }
        }
        
        // JavaScript functions for popup functionality
        function openEventDetails(eventId) {
            const popup = document.getElementById('event-popup-' + eventId);
            popup.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            
            // Add smooth entrance animation
            setTimeout(() => {
                popup.style.opacity = '1';
                const popupContent = popup.querySelector('.popup-content');
                if (popupContent) {
                    popupContent.style.transform = 'scale(1)';
                }
            }, 10);
        }
        
        function closeEventDetails(eventId) {
            const popup = document.getElementById('event-popup-' + eventId);
            popup.style.opacity = '0';
            const popupContent = popup.querySelector('.popup-content');
            if (popupContent) {
                popupContent.style.transform = 'scale(0.9)';
            }
            
            setTimeout(() => {
                popup.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Restore scrolling
            }, 300);
        }

        // Close popup when clicking outside the content or pressing Escape
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('popup-overlay')) {
                    const eventId = event.target.id.split('-').pop();
                    closeEventDetails(eventId);
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const openPopups = document.querySelectorAll('.popup-overlay:not(.hidden)');
                    openPopups.forEach(popup => {
                        const eventId = popup.id.split('-').pop();
                        closeEventDetails(eventId);
                    });
                }
            });
        });
    </script>
</head>
<body class="bg-lightest text-text leading-relaxed">
    <!-- Navbar with lower z-index -->
    <div class="navbar-container">
        <?php include 'navbar.php'; ?>
    </div>
    
    <!-- Banner Section -->
    <header class="hero bg-gradient-to-r from-[rgba(189,150,180,0.4)] to-[rgba(14,13,14,0.9)] bg-cover bg-center bg-no-repeat h-64 md:h-96 flex items-center justify-center text-[#e9d0cb] text-center px-4" style="background-image: url('./BEpics/event2.jpg');">
        <div class="hero-text">
            <h1 class="text-3xl md:text-5xl mb-4 text-center">Share the food & Share the love</h1>
            <p class="text-base md:text-xl max-w-2xl mx-auto text-center">Connecting the world through the joy of cooking and shared recipes.</p>
        </div>
    </header>
    
    <div class="mx-[30px]">
        <div class="bg-white rounded-t-3xl -mt-32 relative z-20 py-10 px-8 shadow-container min-h-screen">
            <!-- Add Event Button for Logged-in Users -->
            <?php if (isset($_SESSION['userID'])): ?>
                <div class="text-center mb-8">
                    <a href="addEvent.php" class="bg-primary hover:bg-medium-pink text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Create New Event
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Success Message Display -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <!-- Debug: Check what's in events array -->
            <?php 
            // Debug output
            echo "<!-- DEBUG: Number of events: " . count($events) . " -->";
            if (!empty($events)) {
                echo "<!-- DEBUG: First event data: " . print_r($events[0], true) . " -->";
            }
            ?>
            
            <!-- Events List -->
            <?php if (empty($events)): ?>
                <!-- Debug information will help us see what's happening -->
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-light-pink mb-4"></i>
                    <h3 class="text-2xl font-bold text-text mb-2">No Events Available</h3>
                    <p class="text-medium-gray mb-6">Be the first to create an event and share your culinary passion!</p>
                    <?php if (isset($_SESSION['userID'])): ?>
                        <a href="addEvent.php" class="bg-primary hover:bg-medium-pink text-white font-bold py-2 px-4 rounded transition-all duration-300">
                            Create Your First Event
                        </a>
                    <?php else: ?>
                        <a href="logIn.php" class="bg-primary hover:bg-medium-pink text-white font-bold py-2 px-4 rounded transition-all duration-300">
                            Login to Create Events
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($events as $index => $event): 
                    // Format the date for display
                    $eventDate = new DateTime($event['eventDate']);
                    $day = $eventDate->format('d');
                    $month = $eventDate->format('M');
                    $year = $eventDate->format('Y');
                    $time = $eventDate->format('g:i A');
                    
                    // Debug the image path
                    echo "<!-- DEBUG: Event Image Path: " . ($event['eventImage'] ?? 'NOT SET') . " -->";
                ?>
                    <div class="flex mb-4 bg-light-yellow rounded-xl overflow-hidden shadow-custom hover:shadow-custom-hover hover:-translate-y-1 transition-all duration-300 border-l-4 border-primary min-h-44">
                        <!-- Date Box -->
                        <div class="bg-primary text-white py-5 px-6 w-28 text-center flex flex-col justify-center flex-shrink-0">
                            <span class="text-3xl font-bold mb-1"><?php echo $day; ?></span>
                            <span class="text-base uppercase tracking-wide font-semibold"><?php echo $month; ?></span>
                            <span class="text-sm mt-2 opacity-90"><?php echo $year; ?></span>
                        </div>
                        
                        <!-- Event Details -->
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div class="mb-3">
                                <span class="font-bold text-primary text-sm tracking-wider bg-primary/10 py-1 px-2 rounded">
                                    <?php echo $time; ?>
                                </span>
                                <h2 class="text-2xl text-text mt-2 mb-1"><?php echo htmlspecialchars($event['title']); ?></h2>
                                <p class="text-medium-gray text-sm mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-auto">
                                <div class="flex space-x-2">
                                    <!-- View Details Button -->
                                    <button onclick="openEventDetails(<?php echo $event['eventID']; ?>)" class="bg-light-pink hover:bg-medium-pink text-text font-bold py-2 px-4 rounded font-bold uppercase tracking-wider text-xs border-2 border-light-pink hover:border-medium-pink transition-all duration-300">
                                        View Details
                                    </button>
                                </div>
                                
                                <!-- Event Image Thumbnail -->
                                <?php if (!empty($event['eventImage'])): ?>
                                    <div class="w-16 h-16 rounded-lg overflow-hidden border-2 border-light-pink">
                                        <!-- FIX: Use the stored path directly -->
                                        <img src="<?php echo htmlspecialchars($event['eventImage']); ?>" 
                                             alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full bg-light-pink flex items-center justify-center\'><i class=\'fas fa-image text-primary\'></i></div>';">
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-lg border-2 border-light-pink bg-light-pink flex items-center justify-center">
                                        <i class="fas fa-image text-primary"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Details Popup - UPDATED with SAME HIGH z-index as resource popup -->
                    <div id="event-popup-<?php echo $event['eventID']; ?>" class="popup-overlay fixed z-[99999] inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
                        <div class="popup-content bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
                            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl z-10 bg-white bg-opacity-90 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110" 
                                    onclick="closeEventDetails(<?php echo $event['eventID']; ?>)">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            <!-- Popup Header with Event Image -->
                            <div class="relative">
                                <?php if (!empty($event['eventImage'])): ?>
                                    <img src="<?php echo htmlspecialchars($event['eventImage']); ?>" 
                                         alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                         class="w-full max-h-[500px] object-contain rounded-t-lg bg-black"
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-80 bg-light-pink rounded-t-lg flex items-center justify-center\'><i class=\'fas fa-calendar-alt text-6xl text-primary\'></i></div>';">
                                <?php else: ?>
                                    <div class="w-full h-80 bg-light-pink rounded-t-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-6xl text-primary"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Event Date Badge -->
                                <div class="absolute top-4 left-4 bg-primary text-white py-2 px-4 rounded-lg text-center shadow-lg">
                                    <span class="text-2xl font-bold block"><?php echo $day; ?></span>
                                    <span class="text-sm uppercase"><?php echo $month; ?></span>
                                </div>
                            </div>
                            
                            <!-- Popup Content -->
                            <div class="p-6">
                                <h2 class="text-3xl font-bold text-text mb-2"><?php echo htmlspecialchars($event['title']); ?></h2>
                                
                                <div class="flex flex-wrap gap-4 mb-4 text-sm text-medium-gray">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-primary"></i>
                                        <span><?php echo $time; ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h3 class="text-xl font-semibold text-text mb-2">About this Event</h3>
                                    <p class="text-medium-gray leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($event['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add horizontal line between events (except after the last one) -->
                    <?php if ($index < count($events) - 1): ?>
                        <hr class="my-5 bg-gradient-to-r from-transparent via-gray-300 to-transparent h-px border-0">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>