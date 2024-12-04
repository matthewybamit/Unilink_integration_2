<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login As</title>
    <link rel="stylesheet" href="assets/css/global.css" />
  </head>
  <?php include 'db_connect.php';?>
  <body>
    <div class="login-as-container">
      <div class="login-as-header">
        <header>LOGIN AS</header>
        <a href="login_auth.html" class="login-as-button" id="login-student"
          >Student</a
        >
        <a href="login_auth.html" class="login-as-button" id="login-faculty"
          >Faculty</a
        >
        <a href="login_auth.html" class="login-as-button" id="login-admin"
          >Admin</a
        >
      </div>
    </div>
    <script src="assets/js/global.js"></script>
  </body>
</html>
