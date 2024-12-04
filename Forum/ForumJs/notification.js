document.addEventListener('DOMContentLoaded', () => {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationCountElement = document.querySelector('.notification-count'); // Notification count element
    const notificationBody = document.getElementById('notification-body');

    function fetchNotifications() {
        fetch('Forum_action.php?fetch_notifications=true')
            .then(response => response.json())
            .then(data => {
                // Clear old notifications
                notificationBody.innerHTML = '';

                if (data.error) {
                    notificationBody.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                // Display notifications
                let notificationHtml = '';

                if (data.likes && data.likes.length) {
                    notificationHtml += '<h4>New Likes:</h4>';
                    data.likes.forEach(like => {
                        notificationHtml += `
                            <div class="notification-item" data-post-id="${like.post_id}">
                                <p>Post: "${like.post_content}" received ${like.like_count} new like(s).</p>
                                ${like.post_image ? `<img src="${like.post_image}" alt="Post Image" style="width: 50px; height: 50px; object-fit: cover;">` : ''}
                                <a href="post-details.php?id=${like.post_id}" class="view-post-link">View Post</a>
                            </div>
                        `;
                    });
                }

                if (data.comments && data.comments.length) {
                    notificationHtml += '<h4>New Comments:</h4>';
                    data.comments.forEach(comment => {
                        notificationHtml += `
                            <div class="notification-item" data-post-id="${comment.post_id}">
                                <p>Post: "${comment.post_content}" received ${comment.comment_count} new comment(s).</p>
                                ${comment.post_image ? `<img src="${comment.post_image}" alt="Post Image" style="width: 50px; height: 50px; object-fit: cover;">` : ''}
                                <a href="post-details.php?id=${comment.post_id}" class="view-post-link">View Post</a>
                            </div>
                        `;
                    });
                }


                if (!notificationHtml) {
                    notificationHtml = '<p>No new notifications.</p>';
                }

                notificationBody.innerHTML = notificationHtml;

                // Update the notification count if new notifications are present
                updateNotificationCount(data.totalCount);
            })
            .catch(error => {
                console.error(error);
                notificationBody.innerHTML = '<p>Error fetching notifications. Please try again later.</p>';
            });
    }

    function updateNotificationCount(count) {
        const lastViewedCount = parseInt(localStorage.getItem('lastViewedNotificationCount')) || 0;

        if (count > lastViewedCount) {
            // Show notification count only if there are new notifications
            notificationCountElement.textContent = count - lastViewedCount; // Show new notifications count
            notificationCountElement.style.display = 'inline';
        } else {
            // Hide the notification count if there are no new notifications
            notificationCountElement.style.display = 'none';
        }

        // Always save the total count in `localStorage`
        localStorage.setItem('lastFetchedNotificationCount', count);
    }

    // Mark notifications as viewed
    function markNotificationsAsViewed() {
        const currentCount = parseInt(localStorage.getItem('lastFetchedNotificationCount')) || 0;
        localStorage.setItem('lastViewedNotificationCount', currentCount); // Update the last viewed count
        notificationCountElement.style.display = 'none'; // Hide the count
    }

    // Toggle the notification dropdown
    notificationBell.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default link behavior

        // Toggle dropdown visibility
        notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';

        // Mark notifications as viewed when the dropdown is opened
        if (notificationDropdown.style.display === 'block') {
            markNotificationsAsViewed();
        }

        // Fetch and display notifications when clicked
        fetchNotifications();
    });

    // Periodically fetch notifications to check for new updates
    setInterval(fetchNotifications, 60000); // Refresh notifications every minute
});
