<?php
error_reporting(0);
include("connection/connect.php");

$query = "SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM post_rating WHERE post_id = 1 AND status = 1";
$result = $db->query($query);
$ratingRow = $result->fetch_assoc();

session_start(); // Start the session

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit();
}

// Fetch user details
$sql = "SELECT * FROM signup WHERE user_id='" . $_SESSION["user_id"] . "'";
$results = mysqli_query($db, $sql);
$row = mysqli_fetch_array($results);
$name = $row['firstname'];

$DISC = $_GET['DISC'];

$sql = "SELECT * FROM full_recipy WHERE rid='$DISC'";
$resul = mysqli_query($db, $sql);
$row = mysqli_fetch_array($resul);

$title = $row['title'];
$title_text = $row['title_text'];
$image = $row['image'];
$ing = $row['ing'];
$ing_text = $row['ing_text'];
$rid = $row['rid'];
$disc = $row['disc'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Full-Recipes</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="rating.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="rating.js"></script>
    <script language="javascript" type="text/javascript">
        $(function() {
            $("#rating_star").codexworld_rating_widget({
                starLength: '5',
                initialValue: '',
                callbackFunctionName: 'processRating',
                imageDirectory: 'images/',
                inputAttr: 'postID'
            });
        });

        function processRating(val, attrVal) {
    <?php if (empty($_SESSION["user_id"])) { ?>
        alert('Please login to rate recipes.');
        return;
    <?php } ?>

    $.ajax({
        type: 'POST',
        url: 'rating.php',
        data: 'postID=' + attrVal + '&ratingPoints=' + val,
        dataType: 'json',
        success: function(data) {
            if (data.status == 'ok') {
                alert('You have rated ' + val + ' to <?php echo $title; ?>');
                $('#avgrat').text(data.average_rating);
                $('#totalrat').text(data.rating_number);
            } else {
                alert('Some problem occurred, please try again.');
            }
        }
    });
}

    </script>
    <style type="text/css">
        .overall-rating {
            font-size: 14px;
            margin-top: 5px;
            color: #8e8d8d;
        }
    </style>
</head>
<body style=" background-color: rgb(212 90 90 / 28%); ">
    <div class="header">
        <div>
            <a href="index.php"><img src="images/logo1.png" alt="Logo"></a>
        </div>
    </div>
    <div class="body">
        <div>
            <div style="min-height:10px;width: 410px;background-color:#e6dfd1;display:inline-block;border-radius:5px;">
                <ul>
                    <input name="rating" value="0" id="rating_star" type="hidden" postID="1" />
                    <div style="min-height:40px;width: 200px;background-color:;float:;">
                        <p style="display:inline-block;min-height:20px;width: 120px;background-color:;">
                            (Average Rating):
                        </p>
                        <p style="float:right;min-height:20px;width: 50px;background-color:;margin-right:30px;">
                            <?php echo $ratingRow['average_rating']; ?>
                        </p>
                        <p style="display:inline-block;height:20px;width: 120px;background-color:;">
                            (based on):
                        </p>
                        <p style="float:right;min-height:20px;width: 50px;background-color:;margin-right:30px;">
                            <?php echo $ratingRow['rating_number']; ?>
                        </p>
                    </div>
                </ul>
            </div>
            <div class="header" style="margin-top:30px;">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="recipes.php">Recipes</a></li>
                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li><a href="login.php">login</a></li>';
                        echo '<li><a href="signup.php">signup</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="body">
                <div id="content">
                    <div>
                        <div>
                            <?php
                            echo '<h3>' . $title . '</h3>';
                            echo '<p>' . $title_text . '</p>';
                            echo "<img style='width:650px;height:350px;margin-top:5px;margin-left:5px;border-radius:5px;' src='admin/img/" . $row['image'] . "' />";
                            echo '<h5>INGREDIENTS</h5>';
                            echo $ing_text;
                            echo '<h5>DIRECTIONS</h5>';
                            echo $disc;
                            ?>
                        </div>
                        <?php include('commentbar.php'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div style="display:<?php echo isset($_SESSION["user_id"]) ? 'block' : 'none'; ?>;">
                <h3>Settings</h3>
                <form action="" method="post">
                    <input type="submit" id="logout" name="logout" value="Logout" style="width:100px;color:#000;border:none;padding:5px;font-size:15px;">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
