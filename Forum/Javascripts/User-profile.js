  // Log out functionality
  document.getElementById('logoutButton').addEventListener('click', () => {
    fetch('logout.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.clear();
                window.location.href = 'unilink.php';
            } else {
                console.error('Error logging out.');
            }
        })
        .catch(error => console.error('Error logging out: ', error));
});

// Load posts with comments on "Posts" button click
document.getElementById('loadPostsButton').addEventListener('click', function() {
    fetch('user_posts.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('postsContainer').innerHTML = `<p>${data.error}</p>`;
                return;
            }

            let postsHtml = '';
            let currentPostId = null;
            
            data.forEach(row => {
                // New post header if it’s a new post
                if (row.post_id !== currentPostId) {
                    if (currentPostId !== null) postsHtml += '</div>'; // Close previous comment section

                    postsHtml += `
                        <div class="post-container">
                            <h3>${row.post_content}</h3>
                            ${row.post_image ? `<img src="${row.post_image}" alt="Post Image" class="post-image">` : ''}
                            <span class="timestamp">Posted on ${new Date(row.post_created_at).toLocaleString()}</span>
                            <div class="comment-section"><h4>Comments</h4>
                    `;
                    currentPostId = row.post_id;
                }

                // Display comment if available
                if (row.comment_id) {
                    postsHtml += `
                        <div class="comment">
                            <p><strong>${row.commenter_username}:</strong> ${row.comment_content}</p>
                            <span class="timestamp">Commented on ${new Date(row.comment_created_at).toLocaleString()}</span>
                        </div>
                    `;
                }
            });

            postsHtml += '</div>'; // Close last comment section
            document.getElementById('postsContainer').innerHTML = postsHtml;
        })
        .catch(error => console.error('Error loading posts:', error));
});