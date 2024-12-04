// JavaScript code to handle AJAX for comment submission
document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(form);
        let postId = form.querySelector('input[name="post_id"]').value;

        // Save the current scroll position before the refresh
        let scrollPosition = window.scrollY;

        fetch('Forum_action.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // After the comment is successfully added, refresh the page
                window.location.href = `${window.location.href.split('?')[0]}?commentPosted=true`;
            } else {
                alert('Failed to post comment');
            }
        });
    });
});

// JavaScript code for reply submission using AJAX
document.querySelectorAll('.reply-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Save the current scroll position before the refresh
        let scrollPosition = window.scrollY;

        // Disable the submit button to prevent multiple clicks
        const submitButton = form.querySelector('button');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Posting...'; // Optional loading feedback

        let formData = new FormData(form);
        let commentId = form.querySelector('input[name="comment_id"]').value;

        fetch('Forum_action.php', {
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
            // Check if the response is successful
            if (data.status === 'success') {
                let reply = data.new_reply;

                // Construct the reply HTML to add to the comment's replies section
                let replyHtml = `
                    <div class="reply" id="reply-${reply.id}">
                        <div class="reply-user-info">
                            <img src="${reply.user_image}" alt="User Avatar" class="reply-avatar-image">
                            <span class="reply-user">${reply.username}</span>
                        </div>
                        <p class="reply-text">${reply.content}</p>
                        <div class="reply-time">${reply.created_at}</div>
                    </div>
                `;

                // Append the new reply to the specific comment's replies section
                const repliesContainer = document.querySelector(`#replies-${commentId}`);
                if (repliesContainer) {
                    repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
                } else {
                    console.error('Replies container not found for comment id:', commentId);
                }

                // After the reply is successfully added, refresh the page
                window.location.href = `${window.location.href.split('?')[0]}?replyPosted=true`;
            } else {
                alert('Failed to post reply');
            }
        })
        .catch(error => {
            // Handle errors (e.g., network issues)
            console.error('Error during fetch:', error);
            alert('An error occurred while posting the reply. Please try again.');
        })
        .finally(() => {
            // Re-enable the submit button and reset text
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Post Reply';
        });
    });
});

// Check if the page was refreshed and restore the scroll position
window.addEventListener('load', function() {
    // Check if the URL contains the 'commentPosted' or 'replyPosted' parameter
    if (window.location.search.includes('commentPosted=true') || window.location.search.includes('replyPosted=true')) {
        // Restore the scroll position after the refresh
        let scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            sessionStorage.removeItem('scrollPosition');
        }

        // Remove the query parameter to avoid a loop of page reloads
        history.replaceState(null, null, window.location.href.split('?')[0]);
    }

    // Save the current scroll position before the page refresh
    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('scrollPosition', window.scrollY);
    });
});
