// Get the modal, the textarea, the button, and the close icon
const createPostModal = document.getElementById('create-post-modal');
const forumPostInput = document.getElementById('forum-post-text');
const createPostButton = document.getElementById('create-post-text');
const closeModalButton = document.getElementById('close-post-modal');

// Show modal when the textarea is clicked
forumPostInput.addEventListener('click', function () {
    createPostModal.style.display = 'block';
});

// Show modal when the "Create Post" button is clicked
createPostButton.addEventListener('click', function () {
    createPostModal.style.display = 'block';
});

// Close modal when the close button is clicked
closeModalButton.addEventListener('click', function () {
    createPostModal.style.display = 'none';
});

// Close the modal when clicking outside the modal content
window.addEventListener('click', function (event) {
    if (event.target === createPostModal) {
        createPostModal.style.display = 'none';
    }
});
