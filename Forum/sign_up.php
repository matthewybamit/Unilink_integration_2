<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="CSS/SignIn.css">
</head>
<style> 

/* Modal Style */
.modal {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
}

textarea {
    width: 100%;
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    padding: 10px 20px;
    margin: 10px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button.secondary {
    background-color: #f44336;
    color: white;
}

button.submit {
    background-color: #4CAF50;
    color: white;
}


</style>
<body>
    <div class="login-container">
        <div class="left-section">
            <img src="images/Building.png" alt="School Building" class="school-image">
        </div>
        <div class="right-section"> 
            <div class="school-logo">
                <img src="images/Logo.png" alt="School Logo">
            </div>
            <a href="unilink.html">
                <img src="images/unilink_logo(2).png" class="Unilink" alt="">
            </a>
            <div class="school-name">
                <p class="school-subtitle">Vicente Malapitan Senior High School</p>
            </div>
            <button class="google-login" id="googleLoginButton">
                <img src="images/google.png" alt="Google Icon"> Log in to your Account
            </button>
        </div>
    </div>
<!-- Modal Structure -->
<div id="banModal" class="modal">
    <div class="modal-content">
        <h4>Your Account is Banned</h4>
        <p>We regret to inform you that your account has been banned. If you believe this is a mistake or wish to appeal, please provide your reason below:</p>
        
        <textarea id="appealMessage" rows="4" placeholder="Explain your appeal here..."></textarea>
        
        <button id="submitAppealBtn" class="btn">Submit Appeal</button>
        <button id="closeModalBtn" class="btn secondary">Close</button>
    </div>
</div>


<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
    import { getAuth, signInWithPopup, GoogleAuthProvider, setPersistence, browserSessionPersistence } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-auth.js";

    const firebaseConfig = {
        apiKey: "AIzaSyCleDtO3LZ6hnkM6JaNZ7i5PygTRWeq3qA",
        authDomain: "ultra-palisade-420202.firebaseapp.com",
        projectId: "ultra-palisade-420202",
        storageBucket: "ultra-palisade-420202.appspot.com",
        messagingSenderId: "35298655443",
        appId: "1:35298655443:web:18e39dfee6f727212ae826",
        measurementId: "G-FK5MZYG8EX"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const provider = new GoogleAuthProvider();
    provider.setCustomParameters({ prompt: 'select_account' });

    // Set session persistence
    setPersistence(auth, browserSessionPersistence);

    const googleLoginButton = document.getElementById('googleLoginButton');
    googleLoginButton.addEventListener('click', () => {
        signInWithPopup(auth, provider)
            .then((result) => {
                const user = result.user;

                // Store user data in session storage
                sessionStorage.setItem('username', user.displayName);
                sessionStorage.setItem('profilePicture', user.photoURL);
                sessionStorage.setItem('email', user.email);
                sessionStorage.setItem('uid', user.uid);

                // Send user data to the PHP session handler for the forum session
                fetch('session_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: user.displayName,
                        profilePicture: user.photoURL,
                        email: user.email,
                        uid: user.uid
                    })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          // Session for the forum is created, redirect to the user profile
                          window.location.href = 'user_posts.php';
                      } else {
                          // Check if the user is banned
                          if (data.message === 'Your account is banned.') {
                              showBanModal();
                          } else {
                              alert('Failed to create session for the forum. Error: ' + data.message);
                          }
                      }
                  });
            })
            .catch((error) => {
                console.error("Error signing in: ", error.message);
                alert("Error signing in: " + error.message);
            });
    });

    // Show modal for banned account
    function showBanModal() {
        const modal = document.getElementById('banModal');
        modal.style.display = 'flex';
        
        const closeBtn = document.getElementById('closeModalBtn');
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        const submitAppealBtn = document.getElementById('submitAppealBtn');
        submitAppealBtn.addEventListener('click', () => {
            const appealMessage = document.getElementById('appealMessage').value;
            
            if (appealMessage.trim() === '') {
                alert("Please provide an appeal message.");
                return;
            }
            
            // Send appeal message to the server
            fetch('submit_appeal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    uid: sessionStorage.getItem('uid'),
                    appealMessage: appealMessage
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert("Your appeal has been submitted. We will review it and get back to you.");
                      modal.style.display = 'none';
                  } else {
                      alert("Failed to submit your appeal. Please try again.");
                  }
              });
        });
    }
</script>

</body>
</html>
