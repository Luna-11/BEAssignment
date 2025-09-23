<?php
session_start();
include('../php/database.php');
include('function.php');

// Ensure user is logged in
if (!isset($_SESSION['customerID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['customerID'];

// Get events from database
$events = getEvent();

// Check if form is submitted for adding new event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $title = sanitizeInput($_POST['title']);
    $eventDate = $_POST['eventDate'];
    $location = sanitizeInput($_POST['location']);
    $description = sanitizeInput($_POST['description']);

    // Validate required fields
    if (empty($title) || empty($eventDate) || empty($location) || empty($description)) {
        $error = "All fields are required.";
    } else {
        // File upload settings
        $uploadDir = "../uploads/events/"; 
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $eventImage = $_FILES['eventImage'];
        $imageName = basename($eventImage['name']);
        $targetFile = $uploadDir . $imageName;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        // Validate file
        if ($eventImage['error'] !== UPLOAD_ERR_OK) {
            $error = "File upload error: " . $eventImage['error'];
        } elseif (!in_array($eventImage['type'], $allowedTypes)) {
            $error = "Only JPG, PNG, or GIF files are allowed.";
        } elseif ($eventImage['size'] > $maxFileSize) {
            $error = "File size must be less than 5MB.";
        } else {
            // Generate unique filename to prevent conflicts
            $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $fileExtension;
            $targetFile = $uploadDir . $uniqueName;

            // Upload file
            if (move_uploaded_file($eventImage['tmp_name'], $targetFile)) {
                // Prepare SQL
                $sql = "INSERT INTO event (title, eventDate, location, description, eventImage, userID) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("sssssi", $title, $eventDate, $location, $description, $uniqueName, $userID);

                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Event created successfully!";
                        header("Location: event.php?success=1");
                        exit();
                    } else {
                        $error = "Database error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error = "Database preparation error: " . $conn->error;
                }
            } else {
                $error = "Failed to upload image.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Christian X</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ocean: #1e3a8a;
            --light-ocean: #3b82f6;
            --sky: #dbeafe;
            --accent: #06b6d4;
            --vanilla: #fff8ddff;
        }

        .event-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
        }

        .btn-accent {
            background: var(--accent);
            color: white;
            border-radius: 2rem;
            transition: 0.3s ease;
        }

        .btn-accent:hover {
            background: var(--light-ocean);
            color: white;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../img/about.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'nav.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero h-64 md:h-96 flex items-center justify-center text-white text-center px-4">
        <div class="hero-text">
            <h1 class="text-3xl md:text-5xl mb-4 font-bold">Upcoming Events</h1>
            <p class="text-base md:text-xl max-w-2xl mx-auto">Join us for exciting activities, workshops, and gatherings</p>
        </div>
    </section>

    <main class="container my-5">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Add Event Button -->
        <div class="text-center mb-8">
            <button class="btn btn-accent px-6 py-3 text-white" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="fas fa-plus me-2"></i>Add New Event
            </button>
        </div>

        <div class="row g-4">
            <?php if (!empty($events)): ?>
                <?php foreach($events as $event): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card event-card h-100">
                            <img src="../uploads/events/<?= htmlspecialchars($event['eventImage'] ?? 'default.jpg') ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($event['title'] ?? 'Event') ?>" 
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='../img/default-event.jpg'">
                            <div class="card-body d-flex flex-column">
                                <!-- Date & Location -->
                                <div class="mb-3">
                                    <?php 
                                    $formattedDate = '';
                                    if (!empty($event['eventDate'])) {
                                        $formattedDate = date("M d, Y • h:i A", strtotime($event['eventDate']));
                                    }
                                    ?>
                                    <div class="p-2 mb-2 bg-blue-50 border-start border-4 border-blue-500 rounded">
                                        <strong class="text-blue-600">📅 <?= htmlspecialchars($formattedDate) ?></strong>
                                    </div>
                                    <span class="text-gray-600 font-semibold">
                                        📍 <?= htmlspecialchars($event['location'] ?? 'Not specified') ?>
                                    </span>
                                </div>

                                <h3 class="card-title text-xl font-bold text-gray-800 mb-2">
                                    <?= htmlspecialchars($event['title'] ?? 'Untitled Event') ?>
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow-1">
                                    <?= htmlspecialchars($event['description'] ?? 'No description available.') ?>
                                </p>
                                
                                <!-- View Details button -->
                                <a href="#" 
                                   class="btn btn-accent view-details mt-auto"
                                   data-bs-toggle="modal" 
                                   data-bs-target="#eventModal"
                                   data-id="<?= $event['eventID'] ?>"
                                   data-title="<?= htmlspecialchars($event['title'] ?? '') ?>"
                                   data-location="<?= htmlspecialchars($event['location'] ?? '') ?>"
                                   data-description="<?= htmlspecialchars($event['description'] ?? '') ?>"
                                   data-date="<?= htmlspecialchars($event['eventDate'] ?? '') ?>"
                                   data-image="../uploads/events/<?= htmlspecialchars($event['eventImage'] ?? 'default.jpg') ?>">
                                   View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-white rounded-lg p-8 shadow">
                        <i class="fas fa-calendar-times text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Events Found</h3>
                        <p class="text-gray-500">Be the first to create an event!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-2xl font-bold" id="eventTitle"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="eventImage" src="" class="img-fluid mb-4 rounded w-full h-64 object-cover" alt="Event Image">
                    <div class="space-y-3">
                        <p><strong class="text-gray-700">Date:</strong> <span id="eventDate" class="text-gray-600"></span></p>
                        <p><strong class="text-gray-700">Location:</strong> <span id="eventLocation" class="text-gray-600"></span></p>
                        <p><strong class="text-gray-700">Description:</strong></p>
                        <p id="eventDescription" class="text-gray-600 bg-gray-50 p-3 rounded"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Event Title *</label>
                            <input type="text" class="form-control" name="title" required maxlength="50" 
                                   value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date & Time *</label>
                            <input type="datetime-local" class="form-control" name="eventDate" required
                                   value="<?= isset($_POST['eventDate']) ? htmlspecialchars($_POST['eventDate']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location *</label>
                            <input type="text" class="form-control" name="location" required maxlength="200"
                                   value="<?= isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" maxlength="500" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Image *</label>
                            <input type="file" class="form-control" name="eventImage" accept="image/*" required>
                            <div class="form-text">Max file size: 5MB. Allowed types: JPG, PNG, GIF</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent text-white">Create Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Event details modal functionality
        const eventModal = document.getElementById('eventModal');
        eventModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            const title = button.getAttribute('data-title');
            const description = button.getAttribute('data-description');
            const location = button.getAttribute('data-location');
            const dateStr = button.getAttribute('data-date');
            const image = button.getAttribute('data-image');

            // Format date
            let formattedDate = '';
            if (dateStr) {
                const eventDate = new Date(dateStr);
                formattedDate = eventDate.toLocaleString('en-US', { 
                    year: 'numeric', month: 'short', day: 'numeric', 
                    hour: 'numeric', minute: '2-digit', hour12: true 
                });
            }

            // Update modal content
            document.getElementById('eventTitle').textContent = title;
            document.getElementById('eventLocation').textContent = location;
            document.getElementById('eventDescription').textContent = description;
            document.getElementById('eventDate').textContent = formattedDate;
            document.getElementById('eventImage').src = image;
        });

        // Auto-close alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    });
    </script>
</body>
</html>