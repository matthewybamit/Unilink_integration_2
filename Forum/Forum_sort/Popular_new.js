
document.addEventListener("DOMContentLoaded", function () {
    const newBtn = document.getElementById('new-btn');
    const popularBtn = document.getElementById('popular-btn');
    
    // Function to update the session sort preference via AJAX
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

    // Event listener for 'New' button
    newBtn.addEventListener('click', function () {
        newBtn.classList.add('active');
        popularBtn.classList.remove('active');
        updateSortPreferenceAndReload('new'); // Update session and reload the page to 'new'
    });

    // Event listener for 'Popular' button
    popularBtn.addEventListener('click', function () {
        popularBtn.classList.add('active');
        newBtn.classList.remove('active');
        updateSortPreferenceAndReload('popular'); // Update session and reload the page to 'popular'
    });
});