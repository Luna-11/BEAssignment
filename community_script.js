
// Modal functionality
let shareRecipeBtn, createPostModal, closePostModal, cancelPost, mediaUpload;
let previewContainer, fileInfo, removeMediaBtn;
let currentPostID = null;
let currentPostTitle = '';

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeModalElements();
    initializeEventListeners();
    initializeCommentListeners();
});

function initializeModalElements() {
    // Get all modal elements
    shareRecipeBtn = document.getElementById('shareRecipeBtn');
    createPostModal = document.getElementById('createPostModal');
    closePostModal = document.getElementById('closePostModal');
    cancelPost = document.getElementById('cancelPost');
    mediaUpload = document.getElementById('media');
    previewContainer = document.getElementById('previewContainer');
    fileInfo = document.getElementById('uploadStatus');
    removeMediaBtn = document.getElementById('removeMedia');
}

function initializeEventListeners() {
    // Modal functionality
    if (shareRecipeBtn && createPostModal) {
        shareRecipeBtn.addEventListener('click', () => {
            createPostModal.classList.remove('hidden');
            createPostModal.classList.add('flex');
        });
    }
    
    if (closePostModal) {
        closePostModal.addEventListener('click', closeModal);
    }

    if (cancelPost) {
        cancelPost.addEventListener('click', closeModal);
    }

    // File upload functionality
    if (mediaUpload) {
        mediaUpload.addEventListener('change', handleFileUpload);
    }

    // Remove media button
    if (removeMediaBtn) {
        removeMediaBtn.addEventListener('click', removeMedia);
    }

    // Close modal when clicking outside
    if (createPostModal) {
        createPostModal.addEventListener('click', function(e) {
            if (e.target === createPostModal) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && createPostModal && !createPostModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Form validation for create post
    const createPostForm = document.getElementById('createPostForm');
    if (createPostForm) {
        createPostForm.addEventListener('submit', validatePostForm);
    }

    // Character counter for post content
    const postContent = document.getElementById('post_content');
    const postCharCount = document.getElementById('postCharCount');
    if (postContent && postCharCount) {
        postContent.addEventListener('input', function() {
            updatePostCharCount(this.value.length);
        });
    }
}

function handleFileUpload(e) {
    const file = e.target.files[0];
    if (previewContainer) {
        previewContainer.classList.add('hidden');
    }
    
    if (fileInfo) {
        fileInfo.textContent = '';
        fileInfo.classList.remove('text-red-500', 'text-orange-500');
    }

    if (file) {
        // Show file information
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        if (fileInfo) {
            fileInfo.textContent = `Selected: ${file.name} (${fileSize} MB)`;
        }

        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            if (fileInfo) {
                fileInfo.textContent = `File too large: ${fileSize} MB (max 10MB)`;
                fileInfo.classList.add('text-red-500');
            }
            mediaUpload.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const mediaPreview = document.getElementById('mediaPreview');
            if (mediaPreview) {
                mediaPreview.innerHTML = '';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';
                    img.className = 'w-full h-64 object-cover rounded-lg';
                    mediaPreview.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.className = 'w-full h-64 object-cover rounded-lg';
                    mediaPreview.appendChild(video);
                } else {
                    if (fileInfo) {
                        fileInfo.textContent = `Unsupported file type: ${file.type}`;
                        fileInfo.classList.add('text-orange-500');
                    }
                    return;
                }
                
                if (previewContainer) {
                    previewContainer.classList.remove('hidden');
                }
            }
        };
        reader.readAsDataURL(file);
    }
}

function removeMedia() {
    if (mediaUpload) {
        mediaUpload.value = '';
    }
    if (previewContainer) {
        previewContainer.classList.add('hidden');
    }
    const mediaPreview = document.getElementById('mediaPreview');
    if (mediaPreview) {
        mediaPreview.innerHTML = '';
    }
    if (fileInfo) {
        fileInfo.textContent = '';
        fileInfo.classList.remove('text-red-500', 'text-orange-500');
    }
}

function validatePostForm(e) {
    const postContent = document.getElementById('post_content');
    if (!postContent) return;
    
    const content = postContent.value.trim();
    
    if (!content) {
        e.preventDefault();
        alert('Please write something about your recipe or cooking experience.');
        return;
    }

    // Validate title format (Title: Description)
    if (!content.includes(':')) {
        if (!confirm('Recommended format: "Recipe Title: Description". Do you want to submit without a title?')) {
            e.preventDefault();
            return;
        }
    }
}

function updatePostCharCount(count) {
    const postCharCount = document.getElementById('postCharCount');
    if (!postCharCount) return;
    
    postCharCount.textContent = `${count}/1000`;
    
    if (count > 900) {
        postCharCount.classList.add('text-red-500');
        postCharCount.classList.remove('text-medium-gray');
    } else {
        postCharCount.classList.remove('text-red-500');
        postCharCount.classList.add('text-medium-gray');
    }
}

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
    
    if (titleElement) {
        titleElement.textContent = `Post: "${postTitle}"`;
    }
    
    const currentCommunityID = document.getElementById('currentCommunityID');
    if (currentCommunityID) {
        currentCommunityID.value = postID;
    }
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    loadComments(postID);
}

// Close comments modal
function closeCommentsModal() {
    const modal = document.getElementById('commentsModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    currentPostID = null;
    currentPostTitle = '';
}

// Load comments for a post
function loadComments(postID) {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;

    commentsList.innerHTML = '<div class="text-center text-medium-gray">Loading comments...</div>';

    fetch(`comment_handler.php?action=get_comments&communityID=${postID}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayComments(data.comments);
                updateCommentCount(postID);
            } else {
                commentsList.innerHTML = `<div class="text-red-500 text-center">Error: ${data.error || 'Unknown error'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            commentsList.innerHTML = '<div class="text-red-500 text-center">Error loading comments. Please try again.</div>';
        });
}

// Display comments in the modal
function displayComments(comments) {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;
    
    if (!comments || comments.length === 0) {
        commentsList.innerHTML = '<div class="text-center text-medium-gray py-8">No comments yet. Be the first to comment!</div>';
        return;
    }

    commentsList.innerHTML = comments.map(comment => `
        <div class="mb-4 pb-4 border-b border-border last:border-b-0">
            <div class="flex justify-between items-start mb-2">
                <div class="font-semibold text-black">
                    ${comment.username || comment.first_name || 'User'}
                </div>
                <div class="text-sm text-medium-gray">
                    ${comment.formattedDate || comment.commentDate || 'Recently'}
                </div>
            </div>
            <p class="text-text">${comment.comment}</p>
        </div>
    `).join('');
}

// Handle adding new comment
function handleAddComment(e) {
    e.preventDefault();
    
    const commentTextElement = document.getElementById('commentText');
    const communityIDElement = document.getElementById('currentCommunityID');
    
    if (!commentTextElement || !communityIDElement) {
        alert('Comment system error. Please refresh the page.');
        return;
    }
    
    const commentText = commentTextElement.value.trim();
    const communityID = communityIDElement.value;
    
    if (!commentText) {
        alert('Please write a comment before posting.');
        return;
    }

    if (!communityID) {
        alert('Invalid post reference. Please try again.');
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
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            commentTextElement.value = '';
            updateCharCount(); // Reset character count
            loadComments(communityID); // Reload comments
        } else {
            alert('Error: ' + (data.error || 'Failed to add comment'));
        }
    })
    .catch(error => {
        console.error('Error adding comment:', error);
        alert('Error adding comment. Please check your connection and try again.');
    });
}

// Update comment count on post
function updateCommentCount(postID) {
    fetch(`comment_handler.php?action=get_comment_count&communityID=${postID}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the comment count in the post
                const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postID}"]`);
                if (commentBtn) {
                    let countSpan = commentBtn.querySelector('.comment-count');
                    if (!countSpan) {
                        countSpan = document.createElement('span');
                        countSpan.className = 'comment-count';
                        commentBtn.querySelector('span').appendChild(countSpan);
                    }
                    countSpan.textContent = data.count;
                    
                    // Update the comment count in the post footer
                    const postFooter = commentBtn.closest('.bg-white').querySelector('.text-sm.text-medium-gray');
                    if (postFooter) {
                        postFooter.textContent = `${data.count} comments`;
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
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-medium-gray');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-medium-gray');
        }
    }
}

function closeModal() {
    if (createPostModal) {
        createPostModal.classList.add('hidden');
        createPostModal.classList.remove('flex');
    }
    
    const createPostForm = document.getElementById('createPostForm');
    if (createPostForm) {
        createPostForm.reset();
    }
    
    if (previewContainer) {
        previewContainer.classList.add('hidden');
    }
    
    const mediaPreview = document.getElementById('mediaPreview');
    if (mediaPreview) {
        mediaPreview.innerHTML = '';
    }
    
    if (fileInfo) {
        fileInfo.textContent = '';
        fileInfo.classList.remove('text-red-500', 'text-orange-500');
    }
    
    // Reset character counters
    const postCharCount = document.getElementById('postCharCount');
    if (postCharCount) {
        postCharCount.textContent = '0/1000';
        postCharCount.classList.remove('text-red-500');
        postCharCount.classList.add('text-medium-gray');
    }
    
    updateCharCount(); // Reset comment character count
}

// Export functions for potential reuse
window.CommunityScripts = {
    closeModal,
    openCommentsModal,
    closeCommentsModal,
    loadComments
};