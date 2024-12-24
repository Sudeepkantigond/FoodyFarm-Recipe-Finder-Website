<?php
include("connection/connect.php");

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];    
    $password = $_POST['password'];

    // Check if the email already exists
    $checkQuery = "SELECT email FROM signup WHERE email = '$email'";
    $checkResult = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Email already exists
        echo "<script>
                alert('User is already registered. Redirecting to the login page.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO signup(firstname, lastname, email, password) VALUES('$fname', '$lname', '$email', '$password')";
        $query = mysqli_query($db, $sql);

        if ($query) {
            header('location:signup_success.php');  // Redirect to signup success page after registration
        } else {
            echo "<script>alert('An error occurred during signup. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body style=" background-color: rgb(212 90 90 / 28%)">
    <div class="header">
        <div>
            <a href="index.php"><img src="images/logo1.png" alt="Logo"></a>
        </div>
    </div>

    <div class="body" width : >
        <div>
            <div class="header">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="recipes.php">Recipes</a></li>
                    <?php
                        if(empty($_SESSION["user_id"])) {
                            echo '<li><a href="login.php">Login</a></li>';
                            echo '<li class="current"><a href="signup.php">Sign Up</a></li>';  // Highlight active page
                        } else {
                            echo '<form action="login.php" method="post">
                                    <input type="submit" id="logout" name="logout" value="Logout" style="width:100px;color:#000;border:none;padding:5px;font-size:15px;"></form>';
                        }
                    ?>
                </ul>
            </div>
            <div class="body">
                <div id="content">
                    <center><h3>Sign Up</h3></center>
                    <form action='' method='post'>
                        <div>
                            <span><label>First Name</label></span>
                            <span><input type="text" value="" name='fname' required></span>
                        
                        
                            <span><label>Last Name</label></span>
                            <span><input type="text" value="" name='lname' required></span>
                        
                            <span><label>Email</label></span>
                            <span><input type="email" value="" name='email' required></span>
                        
                            <span><label>Password</label></span>
                            <span><input type="password" value="" name='password' required></span>
                        </div>
                        <div>
                            <span><input type="submit" name='submit' value="Submit"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div>
        </div>
    </div>
</body>
</html>
