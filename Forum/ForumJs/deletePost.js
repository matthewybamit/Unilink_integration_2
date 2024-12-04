function deletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        // Send a DELETE request via AJAX to the server
        fetch('delete_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                post_id: postId,
                action: 'delete'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                // Optionally, remove the post from the page
                document.getElementById('post-' + postId).remove(); // Assuming each post has an ID like "post-123"
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}


