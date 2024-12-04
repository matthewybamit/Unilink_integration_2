
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/global.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
  </head>
  <?php include 'db_connect.php';?>
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
      <main class="admin-main">
        <div class="admin-stats-cards">
          <div class="admin-card">
            <h2>TOTAL STUDENTS</h2>
            <p>1,193</p>
          </div>
          <div class="admin-card">
            <h2>NEW STUDENTS</h2>
            <p>238</p>
          </div>
          <div class="admin-card">
            <h2>STRAND</h2>
            <p>5</p>
          </div>
          <div class="admin-card">
            <h2>STRAND</h2>
            <p>10</p>
          </div>
        </div>
        <section class="admin-new-students">
          <h2>New Student List</h2>
          <table class="admin-table">
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Assigned Professor</th>
                <th>Date of Admit</th>
                <th>Strand</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Jock Kontin</td>
                <td>Ain Sotou</td>
                <td>August 2021</td>
                <td>Mechanical</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Angelou Lumos</td>
                <td>D</td>
                <td>10 July 2021</td>
                <td>Science</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Pomhig Martins</td>
                <td>Ashbon Con</td>
                <td>10 July 2021</td>
                <td>Science</td>
              </tr>
              <tr>
                <td>4</td>
                <td>Bomon Aurore</td>
                <td>Coro Savine</td>
                <td>July 2021</td>
                <td>Sumodifs</td>
              </tr>
            </tbody>
          </table>
        </section>
      </main>
    </div>
    <script src="assets/js/script.js"></script>
  </body>
</html>
