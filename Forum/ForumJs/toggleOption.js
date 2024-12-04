
function togglePostOptions(postId) {
    const menu = document.getElementById(`post-options-${postId}`);
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}