// JavaScript code to handle AJAX for comment submission
document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(form);
        let postId = form.querySelector('input[name="post_id"]').value;

        // Disable the submit button to prevent multiple clicks
        const submitButton = form.querySelector('button');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Posting...'; // Optional loading feedback

        fetch('Forum_action.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Page refresh after successful comment post
                window.location.reload(); // Refresh the page
            } else {
                alert('Failed to post comment');
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('An error occurred while posting the comment. Please try again.');
        })
        .finally(() => {
            // Re-enable the submit button and reset text
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Post Comment';
        });
    });
});

// JavaScript code for reply submission using AJAX
document.querySelectorAll('.reply-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

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
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Page refresh after successful reply post
                window.location.reload(); // Refresh the page
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
