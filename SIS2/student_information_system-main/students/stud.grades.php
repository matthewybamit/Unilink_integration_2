<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Grades</title>
    <link rel="stylesheet" href="global.css"/>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <style> 
html {
  min-width: 100vw;
  min-height: 100vh;
  overflow: hidden;
  background: #f5f5f5;
}

div::-webkit-scrollbar {
  height: 1px;
  scrollbar-width: thin !important;
}

div::-webkit-scrollbar-thumb {
  border-radius: 60px;
}

/* Login As */

.login-as-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 425px;
  /* height: 490px; */
  padding: 1rem;
  background: #1b0e60;
  border-radius: 1rem;
}

.login-as-header {
  display: flex;
  flex-direction: column;
  text-align: center;
  margin: 1rem 1rem;
}

.login-as-header header {
  color: #d9d9d9;
  font-size: 4rem;
  font-weight: 600;
}

.login-as-button {
  display: flex;
  place-items: center;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  margin-top: 2.5rem;
  background: #e1aa74;
  border-radius: 2.5rem;
  height: 5.5rem;
  border: none;
  outline: none;
  cursor: pointer;
}

.login-as-button {
  text-decoration: none;
  text-transform: uppercase;
  color: #000000;
  font-size: 3.5rem;
  font-weight: 700;
}

/* Login Auth */

.login-auth-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 30rem;
  height: 30rem;
  padding: 1rem 1rem;
  background: #1b0e60;
  border-radius: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-auth-header {
  display: flex;
  flex-direction: column;
  text-align: center;
  justify-content: center;
}

.login-auth-header header {
  color: #d9d9d9;
  font-size: 3.5rem;
  font-weight: 700;
}

.login-google {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 25rem;
  padding: 2rem;
  background: #d9d9d9;
  border-radius: 1rem;
  margin: 5rem;
  cursor: pointer;
  border: none;
  outline: none;
}

.login-google img {
  width: 5rem;
  position: absolute;
  left: 2.5rem;
}

.login-google p {
  font-size: 1.8rem;
  font-weight: 600;
  position: absolute;
  right: 3.5rem;
  text-decoration: none;
}

/* Student Calendar */

.calendar {
  position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 30%;
  background: #ff975e;
  padding: 30px 20px;
  border-radius: 10px;
}

.calendar .header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 2px solid #ccc;
}

.calendar .header .month {
  display: flex;
  align-items: center;
  font-size: 25px;
  font-weight: 600;
  color: #1d1d1d;
}

.calendar .header .btns {
  display: flex;
  gap: 10px;
}

.calendar .header .btns .btn {
  width: 50px;
  height: 40px;
  background: #000;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 5px;
  color: #ff975e;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s;
}

.calendar .header .btns .btn:hover {
  transform: scale(1.05);
}

.calendar .weekdays {
  display: flex;
  gap: 10px;
  margin-bottom: 10px;
}

.calendar .weekdays .day {
  width: calc(100% / 7 - 10px);
  text-align: center;
  font-size: 16px;
  font-weight: 600;
}

.calendar .days {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.calendar .days .day {
  width: calc(100% / 7 - 10px);
  height: 50px;
  background: #d9d9d9;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 5px;
  font-size: 16px;
  font-weight: 600;
  color: #000000;
  transition: all 0.3s;
  user-select: none;
}

.calendar .days .day:not(.next):not(.prev):hover {
  color: #ff975e;
  background: #000;
  transform: scale(1.05);
}

.calendar .days .day.next,
.calendar .days .day.prev {
  color: #ccc;
}

.calendar .days .day.today {
  color: #ff975e;
  background: #000;
}

/* Student Homepage */

.school-header {
  background-color: #1b0e60;
  color: white;
  display: flex;
  flex-direction: row;
  gap: 1.5rem;
  justify-content: center;
  align-items: center;
  padding: 1rem 1rem;
}

.logo-container {
  padding: 10px;
}

.school-logo {
  max-width: 150px;
  max-height: 150px;
}

.school-info {
  text-align: center;
}

.school-info p {
  margin: 5px 0;
}

.welcome-inner-card {
  position: absolute;
  top: 57%;
  left: 50%;
  transform: translate(-50%, -50%);
  border-radius: 50px;
  background-color: #1b0e60;
  display: flex;
  width: 40%;
  max-width: 100%;
  flex-direction: column;
  align-items: center;
  padding: 5rem;
}

.welcome-content {
  display: flex;
  width: 100%;
  max-width: 100%;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.welcome-title {
  position: absolute;
  top: 3%;
  color: #ff975e;
  font-size: 50px;
  font-weight: 700;
  line-height: 1;
}

.profile-image-wrapper {
  background-color: #d9d9d9;
  display: flex;
  margin-top: 24px;
  width: 250px;
  aspect-ratio: 1;
  border-radius: 125px;
  overflow: hidden;
  border: none;
  outline: none;
}

.profile-image {
  aspect-ratio: 1.43;
  object-fit: contain;
  object-position: center;
  width: 100%;
  margin-top: 5px;
  min-height: 20px;
  min-width: 20px;
}

.student-name {
  font-size: 30px;
  font-weight: 800;
  color: #ff975e;
  line-height: 1;
  align-self: center;
  margin-top: 50px;
}

.user-lrn {
  font-size: 30px;
  font-weight: 700;
  color: #ff975e;
  margin-top: 10px;
}

.user-section {
  font-size: 25px;
  font-weight: 700;
  color: #ff975e;
  line-height: 1;
  margin-top: 10px;
  margin-bottom: 10px;
}

.visually-hidden {
  position: absolute;
  width: 1px;
  height: 1px;
  margin: -1px;
  padding: 0;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

.student-home ul {
  position: absolute;
  bottom: 3%;
  left: 15.5%;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.student-home li {
  display: flex;
  background: #ff975e;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 10rem;
  height: 3rem;
  border-radius: 1rem;
}

.student-home a {
  color: #000000;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

/* Students */

.student-container {
  width: 100vw;
  height: 100vh;
  padding: 5rem 5rem;
  background: #192655;
}

.student-right ul {
  position: absolute;
  top: 24%;
  left: 35%;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.student-right li {
  display: flex;
  background: #ff975e;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 10rem;
  height: 3rem;
  border-radius: 1rem;
}

.student-right a {
  color: #000000;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

.stud-grades tr > td,
.stud-schedule tr > td {
  font-weight: bold;
  font-size: 14px;
}

.stud-grades,
.stud-schedule {
  width: 50%;
  background-color: #ff975e;
  border: 1px solid #000;
  border-collapse: collapse;
  position: absolute;
  top: 55%;
  left: 59%;
  transform: translate(-50%, -50%);
}

.stud-grades,
.stud-schedule,
.stud-grades th,
.stud-schedule th,
.stud-grades td,
.stud-schedule td {
  border: 1px solid #000;
  padding: 10px !important;
  font-size: 16px;
}

.stud-grades tr,
.stud-schedule tr {
  text-align: center;
}

.student-left {
  position: absolute;
  bottom: 5rem;
  width: max-content;
  height: 40rem;
  padding: 1.5rem 1.5rem;
  background: #ff975e;
  border-radius: 12px;
}

.student-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.student-box table {
  width: 100%;
}

.student-box th {
  text-align: left;
  font-size: 21px;
}

.student-box td {
  text-wrap: wrap;
  font-weight: 500;
  padding: 0.2rem;
}

.student-box h1 {
  margin-bottom: 25px;
}

.student-box ul {
  list-style: none;
}

.student-box li {
  display: flex;
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #000;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 213px;
  height: 49px;
  border-radius: 12px;
}

.student-box a {
  color: #ff975e;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

/* Admin Homepage */

.admin-left-section {
  position: absolute;
  height: 100%;
  background-color: #1b0e60;
  max-width: 348px;
  width: 100%;
  letter-spacing: 0.3rem;
  margin: 0 auto;
  padding: 1.5rem 2rem 1.5rem;
}

.admin-left-section ul {
  list-style: none;
}

.admin-left-section li {
  margin-bottom: 1.5rem;
  background: #d57744;
  text-align: center;
  align-items: center;
  padding: 1rem 1.5rem;
  cursor: pointer;
  width: 273px;
  height: 76px;
}

.admin-left-section a {
  color: #000000;
  text-decoration: none;
  font-weight: 700;
  font-size: 40px;
}

.admin-sign-out {
  position: absolute;
  bottom: 1.5rem;
  background: #d57744;
  text-align: center;
  align-items: center;
  padding: 1rem 1.5rem;
  cursor: pointer;
  width: 273px;
  height: 76px;
}

.menu-item {
  background-color: rgba(213, 119, 68, 1);
  width: 100%;
  max-width: 411px;
  white-space: nowrap;
  padding: 29px 70px;
  border: 1px solid rgba(0, 0, 0, 1);
  margin-top: 18px;
}

.menu-item:first-child {
  margin-top: 0;
}

.sign-out {
  margin-top: 217px;
  max-width: 424px;
}

.admin-right-section,
.admin-info-edit,
.admin-info-section {
  position: absolute;
  left: 18.1%;
  width: 100%;
  height: 100%;
}

.admin-right-section h1 {
  margin-top: 30px;
  margin-left: 30px;
  font-size: 64px;
  font-weight: 700;
}

.admin-search {
  width: 447px;
  height: 30px;
  display: flex;
  position: absolute;
  top: 7%;
  left: 48%;
  align-items: center;
  padding: 5px;
  border-radius: 10px;
  background: #d57744;
  font-size: 40px;
  font-weight: 700;
}

.admin-search span {
  font-size: 30px;
  font-weight: 700;
}

.admin-search-input {
  font-size: 16px;
  font-weight: 700;
  color: #000000;
  margin-left: 14px;
  outline: none;
  border: none;
  background: transparent;
  width: 447px;
}

.admin-filter-info {
  background: #d57744;
  width: max-content;
  display: flex;
  position: absolute;
  top: 7%;
  left: 71.5%;
  border-radius: 10px;
  align-items: center;
  padding: 7px;
  width: 45px;
  height: 30px;
  border: none;
  cursor: pointer;
}

.admin-filter-info span {
  font-size: 30px;
  font-weight: 700;
}

/* Admin Faculty */

.admin-right-section {
  background-color: #d9d9d9;
}

.admin-container {
  background-color: #d9d9d9;
  width: 72%;
  margin-left: 2rem;
  padding: 1rem;
  border: 1px solid #000;
  overflow: auto;
  height: 64%;
}

/* Admin Member */

.admin-add-btn {
  position: absolute;
  left: 64%;
  background: #24850c;
  font-size: 18px;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  cursor: pointer;
  margin-top: 20px;
  border-radius: 5px;
  width: 170px;
}

.admin-edit-btn {
  background: #cebb18;
  font-size: 24px;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  cursor: pointer;
  margin-top: 1px;
  border-radius: 5px;
  width: 170px;
}

.admin-delete-btn {
  position: absolute;
  left: 77%;
  background: #ce1818;
  font-size: 18px;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  cursor: pointer;
  margin-top: 20px;
  border-radius: 5px;
  width: 170px;
}

.admin-stud-container {
  position: absolute;
  top: 2%;
  left: 1%;
  width: 80%;
  padding: 24px 30px 34px;
  border: 1px solid #000;
}

.strand-btn {
  background: #d57744;
  font-size: 30px;
  height: max-content;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  cursor: pointer;
  margin-bottom: 20px;
  border-radius: 5px;
}

.admin-header {
  background-color: #2c3e50;
  color: white;
  text-align: center;
  padding: 20px;
}

.admin-container table {
  width: 100%;
  border-collapse: collapse;
}

.admin-container table,
.admin-container th,
.admin-container td {
  border: 1px solid #000;
}

.admin-container th,
.admin-container td {
  padding: 1.5px;
  text-align: center;
}

.admin-container td a {
  color: blue !important;
  text-decoration: none;
}

.admin-container a:hover {
  text-decoration: underline;
}

/* Admin Dashboard */

.admin-main {
  margin: 20px;
}

.admin-stats-cards {
  display: flex;
  flex-direction: row;
  gap: 10%;
  margin: 20px 0;
}

.admin-card {
  background-color: #ecf0f1;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  width: max-content;
}

.admin-card h2 {
  margin: 0;
}

.admin-card p {
  font-size: 24px;
  margin: 10px 0 0;
}

.admin-new-students {
  margin: 20px 0;
}

.admin-new-students h2 {
  text-align: left;
}

.admin-table {
  width: 50%;
  border-collapse: collapse;
  margin-top: 10px;
}

.admin-table,
.admin-table th,
.admin-table td {
  border: 1px solid #000;
}

.admin-table th,
.admin-table td {
  padding: 8px;
  text-align: left;
}

/* Admin Student */

/* Admin Student Schedule */

.admin-student-left {
  position: relative;
  width: max-content;
  height: 40rem;
  padding: 1.5rem 1.5rem;
  background: #ff975e;
  border-radius: 12px;
}

.admin-student-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.admin-student-box table {
  width: 100%;
}

.admin-student-box th {
  text-align: left;
  font-size: 21px;
}

.admin-student-box td {
  text-wrap: wrap;
  font-weight: 500;
  padding: 0.2rem;
}

.admin-student-box h1 {
  margin-bottom: 25px;
}

.admin-student-box ul {
  list-style: none;
}

.admin-student-box li {
  display: flex;
  position: absolute;
  top: 95%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #000;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 213px;
  height: 49px;
  border-radius: 12px;
}

.admin-student-box a {
  color: #ff975e;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

.admin-stud-schedule tr > td,
.admin-stud-grades tr > td {
  font-weight: bold;
  font-size: 14px;
}

.admin-stud-schedule,
.admin-stud-grades,
.student-info-edit {
  width: 60%;
  background-color: #ff975e;
  border: 1px solid #000;
  border-collapse: collapse;
  position: absolute;
  top: 53%;
  left: 69%;
  transform: translate(-50%, -50%);
}

.admin-stud-schedule,
.admin-stud-schedule th,
.admin-stud-schedule td,
.admin-stud-grades,
.admin-stud-grades th,
.admin-stud-grades td {
  border: 1px solid #000;
  padding: 10px !important;
  font-size: 16px;
}

.admin-stud-schedule tr,
.admin-stud-grades tr {
  text-align: center;
}

.admin-stud-schedule ul,
.admin-stud-grades ul {
  list-style: none;
}

.admin-stud-schedule li,
.admin-stud-grades li {
  display: flex;
  position: relative;
  top: 50%;
  left: 70%;
  background: #000;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 213px;
  height: 49px;
  border-radius: 12px;
}

.admin-stud-schedule a,
.admin-stud-grades a {
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

.admin-stud-btn ul {
  position: absolute;
  top: 3%;
  left: 40%;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.admin-stud-btn li {
  display: flex;
  background: #ff975e;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 10rem;
  height: 3rem;
  border-radius: 1rem;
}

.admin-stud-btn a {
  color: #000000;
  text-decoration: none;
  font-weight: 700;
  font-size: 30px;
}

.admin-stud-btn button {
  position: relative;
  top: -30px;
  left: 26%;
  background: #ce1818;
  font-size: 18px;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  cursor: pointer;
  margin-top: 20px;
  border-radius: 5px;
  width: 170px;
}

.admin-box-btn a {
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  font-size: 20px;
}

.admin-box-btn li {
  display: flex;
  background: #000;
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 7rem;
  height: 2rem;
  border-radius: 1rem;
}

.admin-box-btn ul {
  position: absolute;
  top: 50px;
  left: 90%;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="date"],
input[type="number"],
select {
  width: 100%;
  padding: 2px;
  box-sizing: border-box;
}

.radio-group {
  gap: 5px;
  display: flex;
  align-items: last baseline;
}

.admin-info-container {
  position: absolute;
  top: 2%;
  left: 1%;
  width: 80%;
  height: 75%;
  padding: 24px 30px 34px;
  border: 1px solid #000;
  overflow: auto;
}
</style>
  <body>
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
          <?php
require_once 'db_connect.php';
$sqlGrades = "
    SELECT 
        strandinfo_tbl.strandname AS strand,
        coursedescription_tbl.coursename,
        grades_tbl.first_quarter,
        grades_tbl.second_quarter,
        grades_tbl.overall_grade,
        CASE 
            WHEN grades_tbl.remarks = 1 THEN 'Pass' 
            ELSE 'Fail' 
        END AS remarks,
        instructorinfo_tbl.insname
    FROM grades_tbl
    JOIN studinfo_tbl ON grades_tbl.LRN = studinfo_tbl.LRN
    JOIN strandinfo_tbl ON studinfo_tbl.strandid = strandinfo_tbl.strandid
    JOIN coursedescription_tbl ON grades_tbl.course_id = coursedescription_tbl.courseid
    JOIN instructorinfo_tbl ON grades_tbl.instructorid = instructorinfo_tbl.instructorid
    WHERE studinfo_tbl.LRN = 'LRN'";

$resultGrades = $conn->query($sqlGrades);
?>

<table class="stud-grades" border="1">
    <tr>
        <th>STRAND</th>
        <th colspan="5">
            <?php 

            if ($resultGrades && $row = $resultGrades->fetch_assoc()) {
                echo htmlspecialchars($row['strand'] ?? 'N/A');
                $resultGrades->data_seek(0); 
            } else {
                echo 'N/A';
            }
            ?>
        </th>
    </tr>
    <tr>
        <th>Subject</th>
        <th>First Quarter</th>
        <th>Second Quarter</th>
        <th>Overall Grade</th>
        <th>Remarks</th>
        <th>Instructor Name</th>
    </tr>
    <?php 
    if ($resultGrades && $resultGrades->num_rows > 0) {
        while ($row = $resultGrades->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['coursename'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['first_quarter'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['second_quarter'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['overall_grade'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['remarks'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['insname'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No grades found.</td></tr>";
    }
    ?>
</table>

      </div>
      <div class="student-left">
        <div class="student-box">
        <h1>Personal Information</h1>
<?php
require_once 'db_connect.php';


$sqlPersonalData = "SELECT firstname, middlename, lastname, extension, LRN, studAge, gender, birthplace, birthDate, studaddress, contactNum, strandid, sectionid,
guardian_firstname,guardian_middlename, guardian_lastname, guardian_extension, guardiancontact, guardianaddress, relationship FROM studinfo_tbl LIMIT 1";
$resultPersonal = $conn->query($sqlPersonalData);


if ($resultPersonal->num_rows > 0) {
    $personalData = $resultPersonal->fetch_assoc();
} else {
    $personalData = null;
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
    </div>
    <script src="assets/js/global.js"></script>
  </body>
</html>
