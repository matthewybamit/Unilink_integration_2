document.getElementById('publishPost').addEventListener('click', () => {
    const postContent = document.getElementById('postContent').value;
    
    // Ensure the user is authenticated
    firebase.auth().onAuthStateChanged((user) => {
        if (user && postContent) {
            const userData = {
                uid: user.uid,
                username: user.displayName,
                post_content: postContent,
            };

            // Send the data to a PHP script to save the post
            fetch('save_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData),
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert("Post published successfully!");
                      modal.style.display = "none"; // Close modal
                  } else {
                      alert("Error publishing post");
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
              });
        }
    });
});
