document.addEventListener("DOMContentLoaded", function () {
    const newBtn = document.getElementById('new-btn');
    const popularBtn = document.getElementById('popular-btn');
    const hotBtn = document.getElementById('hot-btn'); // Hot button
    const commentedBtn = document.getElementById('commented-btn'); // Most Commented button

    // Function to update the session sort preference via AJAX and reload
    function updateSortPreferenceAndReload(sortValue) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_sort_session.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // After updating the session, reload the page to reflect the new state
                location.reload();
            }
        };
        xhr.send('sort=' + sortValue); // Send the sort parameter to update session
    }

    // Function to toggle active class
    function toggleActiveClass(buttonClicked) {
        const buttons = [newBtn, popularBtn, hotBtn, commentedBtn];
        buttons.forEach((btn) => {
            if (btn === buttonClicked) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Event listener for 'New' button
    newBtn.addEventListener('click', function () {
        toggleActiveClass(newBtn);
        updateSortPreferenceAndReload('new'); // Update session and reload the page to 'new'
    });

    // Event listener for 'Popular' button
    popularBtn.addEventListener('click', function () {
        toggleActiveClass(popularBtn);
        updateSortPreferenceAndReload('popular'); // Update session and reload the page to 'popular'
    });

    // Event listener for 'Hot' button
    hotBtn.addEventListener('click', function () {
        toggleActiveClass(hotBtn);
        updateSortPreferenceAndReload('hot'); // Update session and reload the page to 'hot'
    });

    // Event listener for 'Most Commented' button
    commentedBtn.addEventListener('click', function () {
        toggleActiveClass(commentedBtn);
        updateSortPreferenceAndReload('commented'); // Update session and reload the page to 'commented'
    });
});
