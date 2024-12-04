document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Fetch the logged-in user's username and image from the session
        const username = "<?= $_SESSION['username'] ?? 'Guest' ?>"; // Make sure the username is available
        const userImage = "<?= $_SESSION['profilePicture'] ?? 'images/default-avatar.png' ?>"; // The logged-in user's avatar

        const formData = new FormData(form);
        formData.append('username', username);  // Append the username to the form data

        const response = await fetch('Forum_action.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Get the new comment content
            const commentContent = formData.get('comment_content');
            const postContainer = form.closest('.forum-post');
            const commentsContainer = postContainer.querySelector('.comments');

            // Get the current timestamp from the PHP response
            const commentTimestamp = result.comment_created_at;  // Use comment_created_at instead of created_at

            // Create new comment element
            const newComment = document.createElement('div');
            newComment.classList.add('comment');

            // Add username, avatar, comment content, and time ago dynamically
            newComment.innerHTML = `
                <div class="comment-header">
                    <img src="${userImage}" alt="User Avatar" class="comment-avatar">
                    <span class="comment-user">${username}</span>
                    <span class="comment-time" data-time="${commentTimestamp}">Just Now</span>
                </div>
                <p class="comment-text">${commentContent}</p>
            `;

            // Insert the new comment at the top of the comment section
            commentsContainer.insertBefore(newComment, commentsContainer.firstChild);
            form.reset();

            // Start updating the timestamp dynamically
            updateCommentTime(newComment);
        } else {
            alert(result.message);
        }
    });
});

