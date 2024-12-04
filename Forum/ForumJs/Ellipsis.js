function togglePostOptions(postId) {
    const menu = document.getElementById(`post-options-${postId}`);
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

function editPost(postId) {
    // Implement edit functionality here
    alert(`Edit post ${postId}`);
}

function deletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        // Implement delete functionality here, such as an AJAX request to delete the post
        fetch('Forum_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', post_id: postId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Remove the post from the DOM after successful deletion
                document.querySelector(`#post-${postId}`).remove();
            } else {
                alert('Failed to delete post');
            }
        })
        .catch(error => console.error('Error deleting post:', error));
    }
}
