<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Information Edit</title>
    <link rel="stylesheet" href="assets/css/global.css" />
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
    <div class="admin-info-section">
      <div class="admin-info-container">
        <div class="admin-box-btn">
          <button class="admin-add-btn">Save</button>
          <button class="admin-delete-btn">Delete</button>
          <ul>
            <li>
              <a href="admin/admin.stud-schedule.html" class="student-btn"
                >Back</a
              >
            </li>
          </ul>
        </div>
        <h2>Student Information</h2>
        <h3>PERSONAL DATA</h3>
        <form>
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required />
          </div>
          <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" readonly />
          </div>
          <div class="form-group">
            <label>Gender:</label>
            <div class="radio-group">
              <input type="radio" id="male" name="gender" value="male" />
              <label for="male">Male</label>
              <input type="radio" id="female" name="gender" value="female" />
              <label for="female">Female</label>
            </div>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input
              type="date"
              id="dob"
              name="dob"
              required
              onchange="calculateAge()"
            />
          </div>
          <div class="form-group">
            <label for="placeOfBirth">Place of Birth:</label>
            <input type="text" id="placeOfBirth" name="placeOfBirth" required />
          </div>
          <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required />
          </div>
          <div class="form-group">
            <label for="emergencyContactNumber">Contact Number:</label>
            <input
              type="number"
              id="emergencyContactNumber"
              name="emergencyContactNumber"
              pattern="\d{11}"
              maxlength="11"
              required
              oninput="validateContactNumber(this)"
            />
          </div>
          <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="form-group">
            <label for="religion">Religion:</label>
            <select id="religion" name="religion" required>
              <option value="">Select</option>
              <option value="christianity">Christianity</option>
              <option value="islam">Islam</option>
              <option value="hinduism">Hinduism</option>
              <option value="buddhism">Buddhism</option>
              <option value="other">Other</option>
            </select>
          </div>
          <h3>PERSON TO CONTACT IN CASE OF EMERGENCY</h3>
          <div class="form-group">
            <label for="emergencyName">Name:</label>
            <input
              type="text"
              id="emergencyName"
              name="emergencyName"
              required
            />
          </div>
          <div class="form-group">
            <label for="emergencyContactNumber">Contact Number:</label>
            <input
              type="number"
              id="emergencyContactNumber"
              name="emergencyContactNumber"
              pattern="\d{11}"
              maxlength="11"
              required
              oninput="validateContactNumber(this)"
            />
          </div>
          <div class="form-group">
            <label for="emergencyAddress">Address:</label>
            <input
              type="text"
              id="emergencyAddress"
              name="emergencyAddress"
              required
            />
          </div>
          <div class="form-group">
            <label for="relationship">Relationship:</label>
            <select id="relationship" name="relationship" required>
              <option value="">Select</option>
              <option value="parent">Parent</option>
              <option value="sibling">Sibling</option>
              <option value="relative">Relative</option>
              <option value="friend">Friend</option>
              <option value="other">Other</option>
            </select>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
