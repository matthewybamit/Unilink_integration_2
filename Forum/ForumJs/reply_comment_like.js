document.addEventListener('DOMContentLoaded', function () { 
    // Function to handle like/unlike actions
    const handleLike = (button, id, type) => {
        const likeCountElement = button.querySelector('.like-count');
        const endpoint = type === 'comment' ? 'like_comment.php' : 'like_reply.php';

        // Send AJAX request
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `${type}_id=${encodeURIComponent(id)}` // Correctly format the request body
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Parse the new like count from the server response
                    const newLikeCount = data.new_like_count;

                    // Update the like count and button state dynamically
                    likeCountElement.textContent = newLikeCount;

                    if (data.action === 'like') {
                        button.classList.add('liked'); // Optional: Add a visual indicator
                    } else if (data.action === 'unlike') {
                        button.classList.remove('liked'); // Optional: Remove the visual indicator
                    }
                } else {
                    alert(data.message || 'An error occurred while updating the like.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to process the like action. Please try again.');
            });
    };

    // Attach click listeners to like buttons for comments
    document.querySelectorAll('.comment-like-btn[data-comment-id]').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            handleLike(this, commentId, 'comment');
        });
    });

    // Attach click listeners to like buttons for replies
    document.querySelectorAll('.reply-like-btn[data-reply-id]').forEach(button => {
        button.addEventListener('click', function () {
            const replyId = this.getAttribute('data-reply-id');
            handleLike(this, replyId, 'reply');
        });
    });
});