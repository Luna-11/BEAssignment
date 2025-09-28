// Modal functionality
const shareRecipeBtn = document.getElementById('shareRecipeBtn');
const shareRecipeModal = document.getElementById('shareRecipeModal');
const closeShareRecipe = document.getElementById('closeShareRecipe');
const mediaUpload = document.getElementById('mediaUpload');
const fileUploadLabel = document.getElementById('fileUploadLabel');
const previewContainer = document.getElementById('previewContainer');
const fileInfo = document.getElementById('fileInfo');

// Comment functionality
let currentPostID = null;
let currentPostTitle = '';

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    if (shareRecipeBtn) {
        shareRecipeBtn.addEventListener('click', () => {
            shareRecipeModal.classList.remove('hidden');
            shareRecipeModal.classList.add('flex');
        });
    }
    
    if (closeShareRecipe) {
        closeShareRecipe.addEventListener('click', () => {
            closeModal();
        });
    }

    // File upload preview with enhanced feedback
    if (mediaUpload) {
        mediaUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            previewContainer.classList.add('hidden');
            previewContainer.innerHTML = '';
            fileInfo.classList.add('hidden');
            fileInfo.innerHTML = '';

            if (file) {
                // Show file information
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.innerHTML = `Selected: ${file.name} (${fileSize} MB)`;
                fileInfo.classList.remove('hidden');

                if (file.size > 10 * 1024 * 1024) {
                    fileInfo.innerHTML = `<span style="color: red;">File too large: ${fileSize} MB (max 10MB)</span>`;
                    mediaUpload.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = '';
                    
                    // Create remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-media-btn';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.title = 'Remove file';
                    removeBtn.addEventListener('click', function() {
                        mediaUpload.value = '';
                        previewContainer.classList.add('hidden');
                        previewContainer.innerHTML = '';
                        fileInfo.classList.add('hidden');
                    });
                    
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'w-full h-64 object-cover';
                        previewContainer.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.className = 'w-full h-64 object-cover';
                        previewContainer.appendChild(video);
                    } else {
                        fileInfo.innerHTML = `<span style="color: orange;">Unsupported file type: ${file.type}</span>`;
                        return;
                    }
                    
                    previewContainer.appendChild(removeBtn);
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Close modal when clicking outside
    if (shareRecipeModal) {
        shareRecipeModal.addEventListener('click', function(e) {
            if (e.target === shareRecipeModal) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && shareRecipeModal && !shareRecipeModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Form validation
    const recipeForm = document.getElementById('recipeForm');
    if (recipeForm) {
        recipeForm.addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            const description = document.querySelector('textarea[name="description"]').value.trim();
            
            if (!title || !description) {
                e.preventDefault();
                alert('Please fill in both title and description fields.');
            }
        });
    }

    // Initialize comment functionality
    initializeCommentListeners();
});

// Comment functionality
function initializeCommentListeners() {
    // Comment buttons
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postElement = this.closest('.bg-white');
            currentPostID = this.getAttribute('data-post-id');
            const postTitleElement = postElement.querySelector('h3');
            currentPostTitle = postTitleElement ? postTitleElement.textContent.trim() : 'Post';
            openCommentsModal(currentPostID, currentPostTitle);
        });
    });

    // Close comments modal
    const closeComments = document.getElementById('closeComments');
    if (closeComments) {
        closeComments.addEventListener('click', closeCommentsModal);
    }

    // Comments modal background click
    const commentsModal = document.getElementById('commentsModal');
    if (commentsModal) {
        commentsModal.addEventListener('click', function(e) {
            if (e.target === commentsModal) {
                closeCommentsModal();
            }
        });
    }

    // Add comment form submission
    const addCommentForm = document.getElementById('addCommentForm');
    if (addCommentForm) {
        addCommentForm.addEventListener('submit', handleAddComment);
    }

    // Character count for comment textarea
    const commentText = document.getElementById('commentText');
    if (commentText) {
        commentText.addEventListener('input', updateCharCount);
    }
}

// Open comments modal
function openCommentsModal(postID, postTitle) {
    currentPostID = postID;
    currentPostTitle = postTitle;
    
    const modal = document.getElementById('commentsModal');
    const titleElement = document.getElementById('commentsPostTitle');
    
    titleElement.textContent = `Post: "${postTitle}"`;
    document.getElementById('currentCommunityID').value = postID;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    loadComments(postID);
}

// Close comments modal
function closeCommentsModal() {
    const modal = document.getElementById('commentsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentPostID = null;
    currentPostTitle = '';
}

// Load comments for a post
function loadComments(postID) {
    const commentsList = document.getElementById('commentsList');
    commentsList.innerHTML = '<div class="text-center text-medium-gray">Loading comments...</div>';

    fetch(`comment_handler.php?action=get_comments&communityID=${postID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(data.comments);
                updateCommentCount(postID); // Update the comment count on the post
            } else {
                commentsList.innerHTML = `<div class="text-red-500 text-center">Error: ${data.error}</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            commentsList.innerHTML = '<div class="text-red-500 text-center">Error loading comments</div>';
        });
}

// Display comments in the modal
function displayComments(comments) {
    const commentsList = document.getElementById('commentsList');
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<div class="text-center text-medium-gray py-8">No comments yet. Be the first to comment!</div>';
        return;
    }

    commentsList.innerHTML = comments.map(comment => `
        <div class="mb-4 pb-4 border-b border-border last:border-b-0">
            <div class="flex justify-between items-start mb-2">
                <div class="font-semibold text-black">${comment.username}</div>
                <div class="text-sm text-medium-gray">${comment.formattedDate}</div>
            </div>
            <p class="text-text">${comment.comment}</p>
        </div>
    `).join('');
}

// Handle adding new comment
function handleAddComment(e) {
    e.preventDefault();
    
    const commentText = document.getElementById('commentText').value.trim();
    const communityID = document.getElementById('currentCommunityID').value;
    
    if (!commentText) {
        alert('Please write a comment before posting.');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'add_comment');
    formData.append('comment', commentText);
    formData.append('communityID', communityID);

    fetch('comment_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentText').value = '';
            updateCharCount(); // Reset character count
            loadComments(communityID); // Reload comments
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error adding comment:', error);
        alert('Error adding comment. Please try again.');
    });
}

// Update comment count on post
function updateCommentCount(postID) {
    fetch(`comment_handler.php?action=get_comment_count&communityID=${postID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the comment count in the post
                const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postID}"]`);
                if (commentBtn) {
                    let countSpan = commentBtn.querySelector('.comment-count');
                    if (!countSpan) {
                        countSpan = document.createElement('span');
                        countSpan.className = 'comment-count';
                        commentBtn.appendChild(countSpan);
                    }
                    countSpan.textContent = data.count;
                    
                    // Update the comment count in the post footer
                    const postFooter = commentBtn.closest('.bg-white').querySelector('.text-sm.text-medium-gray');
                    if (postFooter) {
                        const likeCount = postFooter.textContent.split('•')[0].trim();
                        postFooter.textContent = `${likeCount} • ${data.count} comments`;
                    }
                }
            }
        })
        .catch(error => console.error('Error updating comment count:', error));
}

// Update character count for comment textarea
function updateCharCount() {
    const textarea = document.getElementById('commentText');
    const charCount = document.getElementById('charCount');
    if (textarea && charCount) {
        const count = textarea.value.length;
        charCount.textContent = `${count}/300`;
        
        // Change color if approaching limit
        if (count > 250) {
            charCount.className = 'text-sm text-red-500';
        } else {
            charCount.className = 'text-sm text-medium-gray';
        }
    }
}

function closeModal() {
    if (shareRecipeModal) {
        shareRecipeModal.classList.add('hidden');
        shareRecipeModal.classList.remove('flex');
    }
    
    const recipeForm = document.getElementById('recipeForm');
    if (recipeForm) {
        recipeForm.reset();
    }
    
    if (previewContainer) {
        previewContainer.classList.add('hidden');
        previewContainer.innerHTML = '';
    }
    
    if (fileInfo) {
        fileInfo.classList.add('hidden');
    }
}

// Export functions for potential reuse
window.CommunityScripts = {
    closeModal,
    openCommentsModal,
    closeCommentsModal,
    loadComments
};