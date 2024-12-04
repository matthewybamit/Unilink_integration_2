<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Calendar</title>
    <link rel="stylesheet" href="/global.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>
    <header class="school-header">
      <div class="logo-container">
        <img
          src="assets/images/user.png"
          alt="Left School Logo"
          class="school-logo"
        />
      </div>
      <div class="school-info">
        <h1><span class="fas fa-school"></span> School Name</h1>
        <p><span class="fas fa-location-arrow"></span> School Address</p>
        <p><span class="fas fa-phone"></span> School Contact Number</p>
      </div>
    </header>
    <div class="student-container">
      <div class="student-right">
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
      <div class="student-left">
        <div class="student-box">
        <h1>Personal Information</h1>
<?php
require_once 'db_connect.php'; // Include the database connection file

// Select personal data
$sqlPersonalData = "SELECT firstname, middlename, lastname, extension, LRN, studAge, gender, birthplace, birthDate, studaddress, contactNum, strandid, sectionid,
guardian_firstname,guardian_middlename, guardian_lastname, guardian_extension, guardiancontact, guardianaddress, relationship FROM studinfo_tbl LIMIT 1";
$resultPersonal = $conn->query($sqlPersonalData);

// Check if personal data exists
if ($resultPersonal->num_rows > 0) {
    $personalData = $resultPersonal->fetch_assoc();
} else {
    $personalData = null; // Default to null if no data is found
}

?>
    <table border="1">
        <tr>
            <th>PERSONAL DATA</th>
        </tr>
        <tr>
            <td><strong>LRN:</strong> <?php echo htmlspecialchars($personalData['LRN'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
    <td><strong>Name:</strong> 
<?php 
        echo htmlspecialchars(
            ($personalData['firstname'] ?? '') . ' ' .
            ($personalData['middlename'] ?? '') . ' ' .
            ($personalData['lastname'] ?? '') . ' ' .
            ($personalData['extension'] ?? '')
        ); 
?>
    </td>
</tr>

        <tr>
            <td><strong>Age:</strong> <?php echo htmlspecialchars($personalData['studAge'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Gender:</strong> <?php echo htmlspecialchars($personalData['gender'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Date of Birth:</strong> <?php echo htmlspecialchars($personalData['birthplace'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Place of Birth:</strong> <?php echo htmlspecialchars($personalData['birthDate'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Address:</strong> <?php echo htmlspecialchars($personalData['studaddress'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Contact Number:</strong> <?php echo htmlspecialchars($personalData['contactNum'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Section:</strong> <?php echo htmlspecialchars($personalData['sectionid'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Strand:</strong> <?php echo htmlspecialchars($personalData['strandid'] ?? 'N/A'); ?></td>
        </tr>
    </table>

    <br />

    <!-- Emergency Contact Table -->
    <table border="1">
        <tr>
            <th>PERSON TO CONTACT IN CASE OF EMERGENCY</th>
        </tr>
        <td><strong>Name:</strong> 
<?php 
        echo htmlspecialchars(
            ($personalData['guardian_firstname'] ?? '') . ' ' .
            ($personalData['guardian_middlename'] ?? '') . ' ' .
            ($personalData['guardian_lastname'] ?? '') . ' ' .
            ($personalData['guardian_extension'] ?? '')
        ); 
?>
    </td>
        <tr>
            <td><strong>Contact Number:</strong> <?php echo htmlspecialchars($personalData['guardiancontact'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Address:</strong> <?php echo htmlspecialchars($personalData['guardianaddress'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Relationship:</strong> <?php echo htmlspecialchars($personalData['relationship'] ?? 'N/A'); ?></td>
        </tr>
    </table>
          <ul>
            <li>
              <a href="login_as.html" class="sign-out-btn" id="sign-out">
                Sign Out
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="calendar">
        <div class="header">
          <div class="month">July 2021</div>
          <div class="btns">
            <!-- today -->
            <div class="btn today">
              <i class="fas fa-calendar-day"></i>
            </div>
            <!-- previous month -->
            <div class="btn prev">
              <i class="fas fa-chevron-left"></i>
            </div>
            <!-- next month -->
            <div class="btn next">
              <i class="fas fa-chevron-right"></i>
            </div>
          </div>
        </div>
        <div class="weekdays">
          <div class="day">Sun</div>
          <div class="day">Mon</div>
          <div class="day">Tue</div>
          <div class="day">Wed</div>
          <div class="day">Thu</div>
          <div class="day">Fri</div>
          <div class="day">Sat</div>
        </div>
        <div class="days"></div>
      </div>
    </div>

    <script src="assets/js/global.js"></script>
  </body>
</html>
