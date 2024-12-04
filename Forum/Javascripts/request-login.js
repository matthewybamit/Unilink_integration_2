 // Check if user is logged in
 if (!sessionStorage.getItem('username')) {
    alert('You need to log in to access your profile.'); // Alert the user
    window.location.href = 'sign_up.php'; // Redirect to sign-up page
} else {
    // Get user data from session storage
    const displayEmail = sessionStorage.getItem('email');
    const username = sessionStorage.getItem('username');
    const profilePicture = sessionStorage.getItem('profilePicture');

    // Display user data
    document.getElementById('username').innerText = username;
    document.getElementById('email').innerText = displayEmail || "Email not provided";
    document.getElementById('profilePicture').src = profilePicture || "images/default-profile.png"; // Default profile image
}

// Log out functionality
document.getElementById('logoutButton').addEventListener('click', () => {
    // Clear session storage
    sessionStorage.clear();
    // Redirect back to sign-in page or reload
    window.location.href = 'unilink.php';
});