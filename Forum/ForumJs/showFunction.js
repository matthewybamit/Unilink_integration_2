document.addEventListener('DOMContentLoaded', function() {
    // Function to load the state from localStorage
    function loadState(postId) {
        const commentsExpanded = localStorage.getItem(`comments-expanded-${postId}`);

        // Restore comments state
        if (commentsExpanded === 'true') {
            document.querySelectorAll(`#comments-${postId} .comment`).forEach((comment, index) => {
                if (index >= 2) {
                    comment.style.display = 'block'; // Show comments after the first two
                }
            });
            document.querySelector(`.view-less-comments[data-post-id='${postId}']`).style.display = 'inline-block';
            document.querySelector(`.show-more-comments[data-post-id='${postId}']`).style.display = 'none';
        } else {
            // Hide comments beyond the first two by default
            document.querySelectorAll(`#comments-${postId} .comment`).forEach((comment, index) => {
                if (index >= 2) {
                    comment.style.display = 'none';
                }
            });
            document.querySelector(`.show-more-comments[data-post-id='${postId}']`).style.display = 'inline-block';
            document.querySelector(`.view-less-comments[data-post-id='${postId}']`).style.display = 'none';
        }

        // Handle replies state (if needed)
        document.querySelectorAll(`#comments-${postId} .replies`).forEach(repliesSection => {
            const commentId = repliesSection.getAttribute('id').split('-')[1];
            const repliesExpanded = localStorage.getItem(`replies-expanded-${commentId}`);
            if (repliesExpanded === 'true') {
                document.querySelectorAll(`#replies-${commentId} .reply`).forEach((reply, index) => {
                    if (index >= 1) {
                        reply.style.display = 'block'; // Show replies after the first one
                    }
                });
                document.querySelector(`.view-less-replies[data-comment-id='${commentId}']`).style.display = 'inline-block';
                document.querySelector(`.show-more-replies[data-comment-id='${commentId}']`).style.display = 'none';
            }
        });
    }

    // Show more comments
    document.querySelectorAll('.show-more-comments').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            document.querySelectorAll(`#comments-${postId} .comment`).forEach((comment, index) => {
                if (index >= 2) {
                    comment.style.display = 'block'; // Show all comments
                }
            });
            document.querySelector(`.view-less-comments[data-post-id='${postId}']`).style.display = 'inline-block';
            this.style.display = 'none';
            localStorage.setItem(`comments-expanded-${postId}`, 'true'); // Save expanded state in localStorage
        });
    });

    // View less comments
    document.querySelectorAll('.view-less-comments').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            document.querySelectorAll(`#comments-${postId} .comment`).forEach((comment, index) => {
                if (index >= 2) {
                    comment.style.display = 'none'; // Hide comments beyond the first two
                }
            });
            document.querySelector(`.show-more-comments[data-post-id='${postId}']`).style.display = 'inline-block';
            this.style.display = 'none';
            localStorage.setItem(`comments-expanded-${postId}`, 'false'); // Save collapsed state in localStorage
        });
    });

    // Show more replies
    document.querySelectorAll('.show-more-replies').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            document.querySelectorAll(`#replies-${commentId} .reply`).forEach((reply, index) => {
                if (index >= 1) {
                    reply.style.display = 'block'; // Show all replies
                }
            });
            document.querySelector(`.view-less-replies[data-comment-id='${commentId}']`).style.display = 'inline-block';
            this.style.display = 'none';
            localStorage.setItem(`replies-expanded-${commentId}`, 'true'); // Save expanded state in localStorage
        });
    });

    // View less replies
    document.querySelectorAll('.view-less-replies').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            document.querySelectorAll(`#replies-${commentId} .reply`).forEach((reply, index) => {
                if (index >= 1) {
                    reply.style.display = 'none'; // Hide replies beyond the first one
                }
            });
            document.querySelector(`.show-more-replies[data-comment-id='${commentId}']`).style.display = 'inline-block';
            this.style.display = 'none';
            localStorage.setItem(`replies-expanded-${commentId}`, 'false'); // Save collapsed state in localStorage
        });
    });

    // On page load, restore the state from localStorage
    document.querySelectorAll('.forum-post').forEach(post => {
        const postId = post.getAttribute('id').split('-')[1];
        loadState(postId); // Load the saved state for each post
    });
});
