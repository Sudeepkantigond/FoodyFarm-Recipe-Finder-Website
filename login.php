<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Check if the user is already logged in, redirect them to the index page
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {

        // Check for user credentials
        $loginquery_user = "SELECT * FROM signup WHERE email='$username' && password='$password'";
        $result_user = mysqli_query($db, $loginquery_user);
        $row_user = mysqli_fetch_array($result_user);

        // Check for admin credentials
        $loginquery_admin = "SELECT * FROM admin_dir WHERE username='$username' && password='$password'";
        $result_admin = mysqli_query($db, $loginquery_admin);
        $row_admin = mysqli_fetch_array($result_admin);

        // If user found
        if (is_array($row_user)) {
            $_SESSION["user_id"] = $row_user['user_id'];
            header("Location: index.php"); // Redirect to home page
            exit(); // Ensure no further code is executed after redirection
        } 
        // If admin found
        elseif (is_array($row_admin)) {
            $_SESSION["a_id"] = $row_admin['a_id'];
            header("Location: admin/dashboard.php"); // Redirect to admin dashboard
            exit(); // Ensure no further code is executed after redirection
        } 
        // If credentials do not match
        else {
            $message = "Invalid Username or Password!";
        }
    }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <!-- Bootstrap -->
    <link href="admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="admin/assets/styles.css" rel="stylesheet" media="screen">
    <script src="admin/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>
  <body id="login">
    <div class="container">
      <form class="form-signin" action='' method='post'>
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="Email address" name="user_name" required>
        <input type="password" class="input-block-level" placeholder="Password" name="password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <input name='submit' class="btn btn-large btn-primary" type="submit" value='Sign In'>
        <center><?php echo '<div style="color:red;">' . $message . '</div>'; ?></center>
      </form>
    </div> <!-- /container -->
    <script src="vendors/jquery-1.9.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
