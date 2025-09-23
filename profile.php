<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    
</head>
<body>
    <?php include('nav.php')?> 
    <div class="profile-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="sidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-title">Account</div>
                        <ul class="sidebar-nav">
                            <li>
                                <a href="#" class="nav-link active" data-section="update-profile">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link" data-section="my-posts">
                                    <i class="fas fa-clipboard-list"></i>
                                    My Posts
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link" data-section="saved-recipes">
                                    <i class="fas fa-bookmark"></i>
                                    Saved Recipes
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link" data-section="saved-posts">
                                    <i class="fas fa-heart"></i>
                                    Saved Posts
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link" data-section="liked-posts">
                                    <i class="fas fa-heart"></i>
                                    Liked Posts
                                </a>
                            </li>

                        </ul>
                    </div>
                    
                    <div class="security-section">
                        <div class="security-title">Security</div>
                        <ul class="sidebar-nav">
                            <li>
                                <a href="logout.php" class="nav-link">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="main-content">
                    <!-- Update Profile Section -->
                    <div id="update-profile" class="content-section active">
                        <div class="content-header">
                            <h2 class="content-title">My Profile</h2>
                        </div>
                        <?php foreach($users as $user):?>
                        <div class="profile-section d-flex" >
                            
                                
                            <?php $image = $user['image'] ?? ''; ?>
                                    <?php if ($image): ?>
                                            <img src="../uploads/userProfile/<?= htmlspecialchars($user['image']) ?>" 
                                                alt="Profile" class="user-avatar">
                                    <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['FirstName'] ?? 'Customer') ?>&background=dbeafe&color=000000ff&size=100" 
                                                alt="Profile" class="user-avatar">
                                    <?php endif; ?>
                               
                                <div class="profile-info mx-3 my-1">
                                    <h3 id="profileName"><?= htmlspecialchars($user['FirstName'] ?? '') ?> <?= htmlspecialchars($user['LastName'] ?? '') ?></h3>
                                    <p id="profileEmail"><?= htmlspecialchars($user['customerEmail'] ?? '') ?> </p>
                                </div>
                            
                        </div>
                        

                        <div class="update-section">
                            <h4>Update Information</h4>
                            <form action="profileAction.php" method="POST" id="profileForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="fName" name="fName" value="<?= htmlspecialchars($user['FirstName'] ?? '') ?>">
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lName" name="lName" value="<?= htmlspecialchars($user['lastName'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['customerEmail'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="media" class="form-label">Upload Image or Video</label>
                                    <input class="form-control" type="file" id="media" name="media" accept="image/jpeg,image/png,image/gif,image/jpg" >
                                </div>
                                <?php endforeach; ?>
                                <button type="submit" class="btn-update">
                                    Update Profile
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <?php
                        $savedRecipes = getSavedReciepe($userID); 
                        ?>

                        <div id="saved-recipes" class="content-section">
                            <div class="content-header">
                                <h2 class="content-title">Saved Recipes</h2>
                            </div>
                            
                            <div id="savedRecipesContainer">
                                <?php if (!empty($savedRecipes)): ?>
                                    <?php foreach($savedRecipes as $recipe): ?>
                                        <?php include 'recipeSaved.php'; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="empty-state text-center py-5">
                                        <i class="fas fa-heart fa-2x mb-3"></i>
                                        <h5>No Saved Recipes</h5>
                                        <p>You haven't saved any recipes yet. Start exploring and save recipes you enjoy!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                         

                
                    <!-- Liked Posts Section -->
                    <?php
                    $likedPosts = getLikedPosts($userID); 
                    ?>

                    <div id="liked-posts" class="content-section">
                        <div class="content-header">
                            <h2 class="content-title">Liked Posts</h2>
                        </div>
                        
                        <div id="likedPostsContainer">
                            <?php if (!empty($likedPosts)): ?>
                                <?php foreach($likedPosts as $post): ?>
                                    <?php include 'post.php'; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-heart"></i>
                                    <h5>No Saved Posts</h5>
                                    <p>You haven't saved any posts yet. Start exploring and save posts you enjoy!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


             <?php
                    $savedPosts = getSavedPosts($userID); 
                    ?>

                    <div id="saved-posts" class="content-section">
                        <div class="content-header">
                            <h2 class="content-title">Saved Posts</h2>
                        </div>
                        
                        <div id="savedPostsContainer">
                            <?php if (!empty($savedPosts)): ?>
                                <?php foreach($savedPosts as $post): ?>
                                    <?php include 'post.php'; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-heart"></i>
                                    <h5>No Saved Posts</h5>
                                    <p>You haven't saved any posts yet. Start exploring and save posts you enjoy!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                                
                    
                    <!-- My Posts Section -->
                    <?php
                    $myPosts = getMyPosts($userID); 
                    ?>

                    <div id="my-posts" class="content-section">
                        <div class="content-header">
                            <h2 class="content-title">My Posts</h2>
                        </div>
                        
                        <div id="myPostsContainer">
                            <?php if (!empty($myPosts)): ?>
                                <?php foreach($myPosts as $post): ?>
                                    <?php include 'post.php'; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-heart"></i>
                                    <h5>No Posts</h5>
                                    <p>You haven't posted any posts yet. Start Sharing posts you enjoy!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            

            if (!this.getAttribute('data-section')) return;
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove('active'));
            contentSections.forEach(s => s.classList.remove('active'));

            this.classList.add('active');

            const sectionId = this.getAttribute('data-section');
            document.getElementById(sectionId).classList.add('active');
        });
    });
}); // <-- closes DOMContentLoaded
</script>



<style>
         :root {
            --ocean: #000e36ff;
            --light-ocean: #3b82f6;
            --sky: #dbeafe;
            --accent: #06b6d4;
            --vanilla: #fff8ddff;
            }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .user-avatar {
            width: 70px;          /* same size for both */
            height: 70px;
            border-radius: 50%;    /* makes it circular */
            object-fit: cover;     /* keeps aspect ratio, fills circle */
            border: 2px solid #dbeafe; /* optional border to match default */
            }

        body {
            font-family: 'Inter', sans-serif;
          
            color: #334155;
            line-height: 1.6;
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .sidebar {
            background: white;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
        }

        .sidebar-nav li {
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-nav li:last-child {
            border-bottom: none;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-nav a:hover {
            background-color: #f8fafc;
            color: var(--ocean);
        }

        .sidebar-nav a.active {
            background-color: var(--sky);
            color: var(--ocean);
            border-right: 3px solid var(--accent);
        }

        .sidebar-nav i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
        }

        .security-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .security-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            padding: 0 1.5rem;
        }

        .main-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-left: 2rem;
        }

        .content-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .content-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--ocean);
            margin-bottom: 0.5rem;
        }

        .profile-section {
            margin-bottom: 2rem;
        }

        .profile-avatar-container {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--light-ocean));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin-right: 1.5rem;
        }

        .profile-info h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--ocean);
            margin-bottom: 0.25rem;
        }

        .profile-info p {
            color: #64748b;
            margin: 0;
        }

        .update-section h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--ocean);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background-color: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
            background-color: white;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .btn-update {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-update:hover {
            background: var(--light-ocean);
            transform: translateY(-1px);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .recipe-card, .post-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .recipe-card:hover, .post-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .recipe-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .recipe-title, .post-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--ocean);
            margin-bottom: 0.5rem;
        }

        .recipe-description, .post-content {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #64748b;
        }

        .post-meta i {
            color: var(--accent);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 1rem;
            }
            
            .main-content {
                margin-left: 0;
                margin-top: 1rem;
            }
            
            .sidebar {
                margin-bottom: 1rem;
            }
        }
    </style>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle comments visibility
        document.querySelectorAll('.comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postCard = this.closest('.post-card');
                const commentsSection = postCard.querySelector('.comments-section');
                
                // Toggle comments section
                commentsSection.classList.toggle('active');
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (commentsSection.classList.contains('active')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.innerHTML = '<i class="fas fa-comment"></i> Hide Comments';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.innerHTML = '<i class="far fa-comment"></i> Comment';
                }
            });
        });
        
        // Like button functionality
        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.innerHTML = '<i class="fas fa-heart"></i> Liked';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.innerHTML = '<i class="far fa-heart"></i> Like';
                }
            });
        });
        
        // Save button functionality
        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.innerHTML = '<i class="far fa-bookmark"></i> Save';
                }
            });
        });
        
       
    </script>
<style>
</body>
</html>