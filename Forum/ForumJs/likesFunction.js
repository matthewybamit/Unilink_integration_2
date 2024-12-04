document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const likeCountElement = this.querySelector('.like-count');

            // Send AJAX request to like_post.php
            fetch('like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update the like count and button state dynamically
                        if (data.action === 'like') {
                            likeCountElement.textContent = parseInt(likeCountElement.textContent) + 1;
                            this.classList.add('liked'); // Optional: Add a visual indicator
                        } else if (data.action === 'unlike') {
                            likeCountElement.textContent = parseInt(likeCountElement.textContent) - 1;
                            this.classList.remove('liked'); // Optional: Remove the visual indicator
                        }
                    } else {
                        alert(data.message); // Show error message
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
});


