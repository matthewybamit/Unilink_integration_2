
// Toggle comment actions (Edit/Delete menu)
function toggleCommentActions(commentId) {
    var menu = document.getElementById('comment-actions-' + commentId);
    var currentDisplay = menu.style.display;

    // Close all other comment action menus
    var allMenus = document.querySelectorAll('.comment-action-menu');
    allMenus.forEach(function(item) {
        item.style.display = 'none';
    });

    // Toggle the current menu
    if (currentDisplay === 'none') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}
function editComment(commentId) {
    var commentElement = document.getElementById('comment-' + commentId);
    var commentTextElement = commentElement.querySelector('.comment-text');

    // Save the current comment text
    var currentText = commentTextElement.textContent;

    // Create an editable text area with the current comment content
    var editArea = document.createElement('textarea');
    editArea.className = 'comment-edit-area';
    editArea.value = currentText;

    // Replace the text element with the text area
    commentElement.replaceChild(editArea, commentTextElement);

    // Create Save and Cancel buttons
    var saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.className = 'save-button';
    saveButton.onclick = function () {
        saveEditedComment(commentId, editArea.value);
    };

    var cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.className = 'cancel-button';
    cancelButton.onclick = function () {
        cancelEditComment(commentId, currentText);
    };

    // Append the buttons
    commentElement.appendChild(saveButton);
    commentElement.appendChild(cancelButton);

    // Ensure the comment-actions menu is visible
    toggleCommentActions(commentId); // Reopen the menu if it was closed
}

function saveEditedComment(commentId, newContent) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Update the comment text on success
                var commentElement = document.getElementById('comment-' + commentId);
                var editArea = commentElement.querySelector('.comment-edit-area');
                var updatedText = document.createElement('p');
                updatedText.className = 'comment-text';
                updatedText.textContent = newContent;

                // Replace the text area with the updated text
                commentElement.replaceChild(updatedText, editArea);

                // Remove the Save and Cancel buttons
                commentElement.querySelectorAll('button.save-button, button.cancel-button').forEach(btn => btn.remove());

                // Ensure the comment-actions menu is visible
                toggleCommentActions(commentId); // Reopen the menu
                alert('Comment updated successfully.');
            } else {
                alert('Error updating comment: ' + response.message);
            }
        }
    };

    xhr.send("comment_id=" + commentId + "&content=" + encodeURIComponent(newContent));
}

function cancelEditComment(commentId, originalContent) {
    var commentElement = document.getElementById('comment-' + commentId);
    var editArea = commentElement.querySelector('.comment-edit-area');
    var originalText = document.createElement('p');
    originalText.className = 'comment-text';
    originalText.textContent = originalContent;

    // Replace the text area with the original text
    commentElement.replaceChild(originalText, editArea);

    // Remove the Save and Cancel buttons
    commentElement.querySelectorAll('button.save-button, button.cancel-button').forEach(btn => btn.remove());

    // Ensure the comment-actions menu is visible
    toggleCommentActions(commentId); // Reopen the menu
}


// Delete comment functionality (with database removal)
function deleteComment(commentId) {
    var confirmation = confirm('Are you sure you want to delete this comment?');
    if (confirmation) {
        // Make an AJAX request to delete the comment from the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_comment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        // Send the comment ID to the server for deletion
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Remove the comment from the DOM if successful
                    document.getElementById('comment-' + commentId).remove();
                    alert('Comment deleted successfully.');
                } else {
                    alert('Error deleting comment: ' + response.message);
                }
            }
        };
        xhr.send("comment_id=" + commentId);  // Make sure comment_id is being sent properly
    }
}



// Toggle reply actions (Edit/Delete menu)
function toggleReplyActions(replyId) {
    var menu = document.getElementById('reply-actions-' + replyId);
    
    // Close all other reply action menus
    var allMenus = document.querySelectorAll('.reply-action-menu');
    allMenus.forEach(function(item) {
        if (item !== menu) {
            item.style.display = 'none'; // Hide all except the clicked one
        }
    });

    // Toggle the visibility of the clicked reply menu
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}

function editReply(replyId) {
    var replyElement = document.getElementById('reply-' + replyId); // Select the reply container
    var replyTextElement = replyElement.querySelector('.reply-text'); // Select the text element

    // Save the current reply text
    var currentText = replyTextElement.textContent;

    // Create an editable text area with the current reply content
    var editArea = document.createElement('textarea');
    editArea.className = 'reply-edit-area';
    editArea.value = currentText;

    // Replace the text element with the text area
    replyElement.replaceChild(editArea, replyTextElement);

    // Create Save and Cancel buttons
    var saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.className = 'save-reply-button';
    saveButton.onclick = function () {
        saveEditedReply(replyId, editArea.value);
    };

    var cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.className = 'cancel-reply-button';
    cancelButton.onclick = function () {
        cancelEditReply(replyId, currentText);
    };

    // Append the buttons
    replyElement.appendChild(saveButton);
    replyElement.appendChild(cancelButton);

    // Ensure the reply-actions menu remains visible
    toggleReplyActions(replyId);
}

function saveEditedReply(replyId, newContent) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_reply.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Update the reply text on success
                var replyElement = document.getElementById('reply-' + replyId);
                var editArea = replyElement.querySelector('.reply-edit-area');
                var updatedText = document.createElement('p');
                updatedText.className = 'reply-text';
                updatedText.textContent = newContent;

                // Replace the text area with the updated text
                replyElement.replaceChild(updatedText, editArea);

                // Remove the Save and Cancel buttons
                replyElement.querySelectorAll('button.save-reply-button, button.cancel-reply-button').forEach(btn => btn.remove());

                // Ensure the reply-actions menu remains functional
                toggleReplyActions(replyId);
                alert('Reply updated successfully.');
            } else {
                alert('Error updating reply: ' + response.message);
            }
        }
    };

    xhr.send("reply_id=" + replyId + "&content=" + encodeURIComponent(newContent));
}

function cancelEditReply(replyId, originalContent) {
    var replyElement = document.getElementById('reply-' + replyId);
    var editArea = replyElement.querySelector('.reply-edit-area');
    var originalText = document.createElement('p');
    originalText.className = 'reply-text';
    originalText.textContent = originalContent;

    // Replace the text area with the original text
    replyElement.replaceChild(originalText, editArea);

    // Remove the Save and Cancel buttons
    replyElement.querySelectorAll('button.save-reply-button, button.cancel-reply-button').forEach(btn => btn.remove());

    // Ensure the reply-actions menu remains functional
    toggleReplyActions(replyId);
}


// Delete reply functionality (with database removal)
function deleteReply(replyId) {
    var confirmation = confirm('Are you sure you want to delete this reply?');
    if (confirmation) {
        console.log("Deleting reply with ID: " + replyId);  // Log the replyId
        
        // Make an AJAX request to delete the reply from the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_reply.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        // Send the reply ID to the server for deletion
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log(response);  // Log the response from the server
                
                if (response.success) {
                    // Remove the reply from the DOM if successful
                    document.getElementById('reply-' + replyId).remove();
                    alert('Reply deleted successfully.');
                } else {
                    alert('Error deleting reply: ' + response.message);
                }
            }
        };
        xhr.send("reply_id=" + replyId);
    }
}
