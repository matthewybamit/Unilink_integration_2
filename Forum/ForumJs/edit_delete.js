function togglePostOptions(postId) {
    const menu = document.getElementById(`post-options-${postId}`);
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

function enableEditPost(postId) {
    document.getElementById(`post-content-${postId}`).style.display = 'none';
    document.getElementById(`edit-content-${postId}`).style.display = 'block';
}

function cancelEditPost(postId) {
    document.getElementById(`post-content-${postId}`).style.display = 'block';
    document.getElementById(`edit-content-${postId}`).style.display = 'none';
}
