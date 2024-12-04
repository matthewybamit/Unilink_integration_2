document.addEventListener("DOMContentLoaded", function() {
    const replyToReplyButtons = document.querySelectorAll('.reply-to-reply');
    
    replyToReplyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-id');
            const commentId = this.getAttribute('data-comment-id');
            const username = this.getAttribute('data-username');
            
            // Create the reply form dynamically if it doesn't exist
            let replyForm = document.getElementById('reply-to-reply-form-' + parentId);
            if (!replyForm) {
                replyForm = document.createElement('form');
                replyForm.setAttribute('method', 'POST');
                replyForm.setAttribute('class', 'reply-to-reply-form');
                replyForm.setAttribute('id', 'reply-to-reply-form-' + parentId);

                replyForm.innerHTML = `
                    <input type="hidden" name="comment_id" value="${commentId}">
                    <input type="hidden" name="parent_reply_id" value="${parentId}">
                    <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                    <input type="text" name="reply_content" placeholder="Write a reply..." required>
                    <button type="submit"><i class="fa-solid fa-paper-plane"></i> Reply</button>
                `;
            }

            // Pre-fill the text input with @username and the original reply content
            const replyInput = replyForm.querySelector('input[name="reply_content"]');
           

            // Find the parent comment section (not the reply container)
            const commentSection = document.getElementById('comment-' + commentId);
            replyInput.value = '@' + username + ' ' + "Replying to: " + document.querySelector(`#reply-${parentId} .reply-text`).textContent;
            // Check if the form already exists, if not, append it
            const existingForm = commentSection.querySelector('.reply-to-reply-form');
            if (!existingForm) {
                // Append the reply form to the comment section (not the replies container)
                commentSection.appendChild(replyForm);
            }

            // Show the reply form (in case it's hidden)
            replyForm.style.display = 'block';
        });
    });

    // Handle showing more replies
    const showMoreReplyButtons = document.querySelectorAll('.show-more-replies');
    showMoreReplyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const repliesContainer = document.getElementById('replies-' + commentId);
            const hiddenReplies = repliesContainer.querySelectorAll('.reply[style="display: none;"]');
            hiddenReplies.forEach(reply => {
                reply.style.display = 'block';
            });
            this.style.display = 'none'; // Hide the "show more" button
            const viewLessButton = repliesContainer.querySelector('.view-less-replies');
            viewLessButton.style.display = 'inline';
        });
    });

    // Handle viewing less replies
    const viewLessReplyButtons = document.querySelectorAll('.view-less-replies');
    viewLessReplyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const repliesContainer = document.getElementById('replies-' + commentId);
            const replies = repliesContainer.querySelectorAll('.reply');
            for (let i = 1; i < replies.length; i++) {
                replies[i].style.display = 'none';
            }
            this.style.display = 'none'; // Hide the "view less" button
            const showMoreButton = repliesContainer.querySelector('.show-more-replies');
            showMoreButton.style.display = 'inline';
        });
    });
});
