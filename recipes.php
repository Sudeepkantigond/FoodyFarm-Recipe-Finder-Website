<?php
include('connection/connect.php');
session_start(); // Session started by unique user_id
error_reporting(0); // For printing the text

// Check if the user is logged in
$sql = "SELECT * FROM signup WHERE user_id='" . $_SESSION["user_id"] . "'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$name = $row['firstname'];

if ($_SESSION["user_id"] == 0) {
    $none = 'none';
}

// Search query processing
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($db, $_POST['search_query']); // Sanitize input
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recipes</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body style=" background-color: rgb(212 90 90 / 28%)">
    <div class="header">
        <div>
            <a href="index.php"><img src="images/logo1.png" alt="Logo"></a>
        </div>
    </div>
    <div class="body">
        <div>
            <div class="header">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li class="current"><a href="recipes.php">Recipes</a></li>

                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li><a href="login.php">login</a></li>';
                        echo '<li><a href="signup.php">signup</a></li>';
                    } else {
                        $logout = '<form action="login.php" method="post">
                            <input type="submit" id="logout" name="logout" value="logout" style="width:100px;color:#000;border:none;padding:5px;font-size:15px;">
                        </form>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Search Form -->
            <div class="search-bar" style="margin: 20px 100px; width:auto; text-align: center;">
    <form method="POST" action="recipes.php" style="display: inline-block;">
        <input type="text" name="search_query" placeholder="Search for recipes..." value="<?php echo $search_query; ?>"
            style="padding: 10px; width: 60%; font-size: 16px; border-radius: 5px; border: 1px solid #ccc;">
        <input type="submit" name="search" value="Search"
            style="padding: 10px 20px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; background-color: #5cb85c; color: white;">
    </form>
</div>

            <div class="body">
                <div id="content">
                    <div>
                        <ul>
                            <?php
                            // Modify SQL query to search based on the user's input
                            $sql = "SELECT * FROM recipes WHERE resname LIKE '%$search_query%' ORDER BY rid DESC";
                            $result = mysqli_query($db, $sql);

                            while ($row = mysqli_fetch_array($result)) {
                                $rid = $row['rid'];
                                $rimage = $row['rimage'];
                                $rname = $row['resname'];
                                $rtext = $row['rtext'];

                                echo '<li>';
                                echo "<a href=fullrecipy.php?DISC=" . $row['rid'] . "><img style='width:150px; height:180px; margin-top:5px; margin-left:5px; border-radius:5px;' src='admin/img/" . $row['rimage'] . "'></a>";
                                echo '<div>';
                                echo "<h3><a href=fullrecipy.php?DISC=" . $row['rid'] . ">$rname</a></h3>";
                                echo "<p>$rtext</p>";
                                echo '</div>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
