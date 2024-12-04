document.querySelector('#comment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('Forum_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Dynamically add the new comment to the page
            let commentSection = document.querySelector('#comments');
            commentSection.innerHTML += `
                <div class="comment" id="comment-${data.new_comment.id}">
                    <img src="${data.new_comment.user_image}" alt="${data.new_comment.username}">
                    <span>${data.new_comment.username}</span>
                    <p>${data.new_comment.content}</p>
                    <small>${data.new_comment.created_at}</small>
                </div>
            `;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
    });
});

// Handle reply submission similarly by updating the relevant comment's reply section dynamically
