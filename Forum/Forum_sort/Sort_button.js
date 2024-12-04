document.addEventListener("DOMContentLoaded", function () {
    const newBtn = document.getElementById('new-btn');
    const popularBtn = document.getElementById('popular-btn');
    const hotBtn = document.getElementById('hot-btn');  // Add Hot button
    const postContainer = document.createElement('div');
    
    postContainer.classList.add('forum-posts-container');
    const forumPosts = Array.from(document.querySelectorAll('.forum-post'));
    document.querySelector('.forum-content').appendChild(postContainer);
    forumPosts.forEach(post => postContainer.appendChild(post));

    const originalPosts = [...forumPosts];

    function sortPostsByLikes() {
        const sortedPosts = [...forumPosts].sort((a, b) => {
            const likeCountA = parseInt(a.querySelector('.like-count').textContent);
            const likeCountB = parseInt(b.querySelector('.like-count').textContent);
            return likeCountB - likeCountA;
        });

        postContainer.innerHTML = '';
        sortedPosts.forEach(post => postContainer.appendChild(post));
    }

    // Function to sort posts by likes within the last 24 hours (Hot)
    function sortPostsByHot() {
        const now = new Date();
        const sortedPosts = [...forumPosts].filter(post => {
            const postDate = new Date(post.querySelector('.post-date').textContent);  // Assuming you have a post-date class
            const timeDifference = Math.abs(now - postDate);
            const hoursDifference = timeDifference / (1000 * 60 * 60); // Convert to hours
            return hoursDifference <= 24;  // Hot posts within the last 24 hours
        }).sort((a, b) => {
            const likeCountA = parseInt(a.querySelector('.like-count').textContent);
            const likeCountB = parseInt(b.querySelector('.like-count').textContent);
            return likeCountB - likeCountA;  // Sort by likes
        });

        postContainer.innerHTML = '';
        sortedPosts.forEach(post => postContainer.appendChild(post));
    }

    function revertToOriginalOrder() {
        postContainer.innerHTML = '';
        originalPosts.forEach(post => postContainer.appendChild(post));
    }

    function updateSortPreference(sortValue) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_sort_session.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('sort=' + sortValue);
    }

    newBtn.addEventListener('click', function () {
        newBtn.classList.add('active');
        popularBtn.classList.remove('active');
        hotBtn.classList.remove('active');
        revertToOriginalOrder();
        updateSortPreference('new');
    });

    popularBtn.addEventListener('click', function () {
        popularBtn.classList.add('active');
        newBtn.classList.remove('active');
        hotBtn.classList.remove('active');
        sortPostsByLikes();
        updateSortPreference('popular');
    });

    hotBtn.addEventListener('click', function () {
        hotBtn.classList.add('active');
        newBtn.classList.remove('active');
        popularBtn.classList.remove('active');
        sortPostsByHot();  // Sort posts by hot/trending criteria
        updateSortPreference('hot');
    });
});
