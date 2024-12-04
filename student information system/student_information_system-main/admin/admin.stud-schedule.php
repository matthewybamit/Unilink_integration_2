<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Student Schedule</title>
    <link rel="stylesheet" href="assets/css/global.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <?php include 'db_connection.php';?>
  <body>
    <div class="admin-left-section">
      <ul>
        <li>
          <a href="admin/admin.dashboard.html" class="admin-item">Admin</a>
        </li>
        <li>
          <a href="admin/admin.stud.html" class="admin-item">Student</a>
        </li>
        <li><a href="#" class="admin-item">Settings</a></li>
      </ul>
      <a href="login_as.html" class="admin-sign-out" id="admin-sign-out"
        >Sign Out</a
      >
    </div>

    <header class="school-header">
      <div class="logo-container">
        <img
          src="assets/images/user.png"
          alt="School Logo"
          class="school-logo"
        />
      </div>
      <div class="school-info">
        <h1><span class="fas fa-school"></span> School Name</h1>
        <p><span class="fas fa-location-arrow"></span> School Address</p>
        <p><span class="fas fa-phone"></span> School Contact Number</p>
      </div>
    </header>

    <div class="admin-right-section">
      <div class="admin-stud-container">
        <div class="admin-stud-btn">
          <ul>
            <li>
              <a href="admin/admin.stud-schedule.html" class="student-btn"
                >Schedule</a
              >
            </li>
            <li>
              <a href="admin/admin.stud-grades.html" class="student-btn"
                >Grades</a
              >
            </li>
            <li>
              <a href="admin/admin.stud.html" class="student-btn">Back</a>
            </li>
            <button>Delete</button>
          </ul>
        </div>
        <table class="admin-stud-schedule">
          <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Schedule</th>
            <th>Subject Teacher</th>
          </tr>
          <tr>
            <td>1</td>
            <td contenteditable="true">Oral Communication</td>
            <td contenteditable="true">07:00AM - 07:59 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>2</td>
            <td contenteditable="true">General Mathematics</td>
            <td contenteditable="true">08:00AM - 08:59 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>3</td>
            <td contenteditable="true">Earth and Life Science</td>
            <td contenteditable="true">09:00AM - 09:59 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>4</td>
            <td contenteditable="true">
              21ST Century Literature from the Philippines and the World Century
            </td>
            <td contenteditable="true">10:30AM - 11:30 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>5</td>
            <td contenteditable="true">
              Komunikasyon at Pananaliksik sa wika at kulturang Filipino
            </td>
            <td contenteditable="true">11:30AM - 12:30 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>6</td>
            <td contenteditable="true">Personal Development</td>
            <td contenteditable="true">01:30AM - 2:30 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>7</td>
            <td contenteditable="true">Physical Education and Health</td>
            <td contenteditable="true">02:30AM - 3:30 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr>
            <td>8</td>
            <td contenteditable="true">Empowerment Technologies</td>
            <td contenteditable="true">03:30AM - 4:30 AM</td>
            <td contenteditable="true">Teacher</td>
          </tr>
          <tr></tr>
        </table>

        <div class="admin-student-left">
          <div class="admin-student-box">
            <h2>Student Information</h2>
            <table>
              <th>PERSONAL DATA</th>
              <tr>
                <td><strong>Name:</strong> Señora, Franz Jeremy Villalon</td>
              </tr>
              <tr>
                <td><strong>Age:</strong> 21</td>
              </tr>
              <tr>
                <td><strong>Gender:</strong> Male</td>
              </tr>
              <tr>
                <td><strong>Date of Birth:</strong> June 07, 2003</td>
              </tr>
              <tr>
                <td><strong>Place of Birth:</strong> Quezon City</td>
              </tr>
              <tr>
                <td>
                  <strong>Address:</strong> 2474 Ilang-Ilang St. Almar Subd
                  Caloocan City
                </td>
              </tr>
              <tr>
                <td><strong>Contact Number:</strong> (+63)912 3456 789</td>
              </tr>
              <tr>
                <td><strong>Email Address:</strong> example@gmail.com</td>
              </tr>
              <tr>
                <td><strong>Religion:</strong> Roman Catholic</td>
              </tr>
            </table>
            <br />
            <table>
              <th>PERSON TO CONTACT IN CASE OF EMERGENCY</th>
              <tr>
                <td><strong>Name:</strong> Guardian's Name</td>
              </tr>
              <tr>
                <td>
                  <strong>Contact Number:</strong> Guardian's Contact Number
                </td>
              </tr>
              <tr>
                <td><strong>Address:</strong> Same as Above</td>
              </tr>
              <tr>
                <td>
                  <strong>Relationship:</strong> Father, Mother, Sister,
                  Brother, etc.
                </td>
              </tr>
            </table>
            <ul>
              <li>
                <a href="admin/admin.information-edit.html" id="edit-btn">
                  Edit
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/script.js"></script>
  </body>
</html>
