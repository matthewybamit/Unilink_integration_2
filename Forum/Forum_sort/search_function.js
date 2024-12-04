function filterPosts() {
    const query = document.getElementById("search-bar").value.toLowerCase();
    const posts = document.querySelectorAll(".forum-post");

    posts.forEach(post => {
        const postContent = post.querySelector(".post-body p").textContent.toLowerCase();
        const postAuthor = post.querySelector(".username").textContent.toLowerCase();

        if (postContent.includes(query) || postAuthor.includes(query)) {
            post.style.display = "block";
        } else {
            post.style.display = "none";
        }
    });
}



document.getElementById("search-bar").addEventListener("keyup", function() {
    const query = this.value;
    fetch(`search_posts.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(posts => {
            const forumContent = document.querySelector(".forum-content");
            forumContent.innerHTML = ""; // Clear current posts
            posts.forEach(post => {
                const postHTML = `
                    <div class="forum-post">
                        <div class="post-header">
                            <div class="post-user-info">
                                <span class="username">${post.username}</span>
                                <span class="timestamp">${post.created_at}</span>
                            </div>
                        </div>
                        <div class="post-body">
                            <p>${post.content}</p>
                        </div>
                    </div>
                `;
                forumContent.innerHTML += postHTML;
            });
        });
});