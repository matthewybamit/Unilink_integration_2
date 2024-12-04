<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Homepage</title>
    <link rel="stylesheet" href="/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <header class="school-header">
      <div class="logo-container">
        <img src="assets/images/user.png" alt="Left School Logo" class="school-logo">
      </div>
      <div class="school-info">
        <h1><span class="fas fa-school"></span> School Name</h1>
        <p><span class="fas fa-location-arrow"></span> School Address</p>
        <p><span class="fas fa-phone"></span> School Contact Number</p>
      </div>
    </header>
      <div class="welcome-inner-card">
        <div class="student-home">
          <ul>
            <li>
              <a href="stud.schedule.php" class="student-btn"
                >Schedule</a
              >
            </li>
            <li>
              <a href="stud.grades.php" class="student-btn">Grades</a>
            </li>
            <li>
              <a href="stud.calendar.php" class="student-btn"
                >Calendar</a
              >
            </li>
          </ul>
        </div>
        <div class="welcome-content">
          <h1 class="welcome-title">WELCOME</h1>
          <div class="profile-image-wrapper">
            <img
              loading="lazy"
              src="assets/images/user.png"
              class="profile-image"
              alt="Student Picture"
            />
          </div>
          <h2 class="student-name">
    <?php 
    echo htmlspecialchars(
        ($personalData['firstname'] ?? '') . ' ' .
        ($personalData['middlename'] ?? '') . ' ' .
        ($personalData['lastname'] ?? '') . ' ' .
        ($personalData['extension'] ?? '')
    ); 
    ?>
</h2>
<p class="user-lrn">
    <?php echo htmlspecialchars($personalData['LRN'] ?? 'N/A'); ?>
</p>
<p class="user-section">
    <?php echo htmlspecialchars($personalData['strandid'] ?? 'N/A'); ?>
</p>
        </div>
      </div>
    </div>
    <script src="assets/js/global.js"></script>
  </body>
</html>
