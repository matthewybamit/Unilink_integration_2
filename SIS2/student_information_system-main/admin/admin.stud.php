<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Student</title>
    <link rel="stylesheet" href="global.css" />
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
      <h1>Students</h1>
      <div class="admin-search">
        <span class="admin-search-icon material-symbols-outlined">Search</span>
        <input class="admin-search-input" type="search" placeholder="Search" />
      </div>
      <button class="admin-filter-info">
        <span class="material-symbols-outlined" id="student-filter">
          filter_alt
        </span>
      </button>

      <div class="admin-container">
        <div class="admin-box-btn">
          <!-- <button class="admin-edit-btn">Edit</button>
          <button class="admin-delete-btn">Delete</button> -->
        </div>
        <button class="strand-btn">STEM</button>
        <button class="strand-btn">ABM</button>
        <button class="strand-btn">GAS</button>
        <button class="strand-btn">HUMSS</button>
        <button class="strand-btn">TVL</button>
        <button class="admin-add-btn">Add Student</button>

  


      </div>
    </div>
  </body>
</html>
