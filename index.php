<?php
include('connection/connect.php');
session_start(); // Start session to manage user login state
error_reporting(0);

$sql = "SELECT * FROM signup WHERE user_id='" . $_SESSION["user_id"] . "'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$name = $row['firstname'];

if ($_SESSION["user_id"] == 0) {
    $none = 'none';
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Food &amp; Recipes Web Template</title>
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

        var userId = <?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 'null'; ?>;

        function processRating(val, attrVal) {
            if (!userId) {
                alert("Please login first!!");
                window.location.href = 'login.php';
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'rating.php',
                data: 'postID=' + attrVal + '&ratingPoints=' + val,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'ok') {
                        alert('You have rated ' + val + ' to CodexWorld');
                        $('#avgrat').text(data.average_rating);
                        $('#totalrat').text(data.rating_number);
                    } else {
                        alert('Some problem occurred, please try again.');
                    }
                }
            });
        }
    </script>
    
</head>
<body style="width:auto; background-color: rgb(212 90 90 / 28%); ">
    <div>
    <div class="header">
        <div>
            <a href="index.php"><img src="images/logo1.png" alt="Logo"></a>
        </div>
    </div>
    <div style="background-color:bisque; margin:auto; min-height:20px; display:<?php echo $none; ?>;">
        <div style="background-color:; margin-left:200px; width:950px; min-height:30px;">
            <p style='float:left; color:#449c3a; margin-left:10px; font-size:20px;'>You successfully login!</p>
            <ul>
               
            <p style='float:right; color:black; font-size:20px;'>Welcome:<span style='margin-right:0px;'><?php echo $name ?></span>
                <img src='images/user.png' style='height:20px; width:20px; margin-top:5px; margin-right:5px;' />
                
            </p>
            </li>
            
            <div style="display:<?php echo isset($_SESSION["user_id"]) ? 'block' : 'none'; ?>;">
                    <form action="" method="post">
                        <input type="submit" id="logout" name="logout" value="Logout" style="width:100px;color:#000;border:none;padding:5px;font-size:15px;">
                    </form>
                </div>
                </li>
            </ul>
        </div>  
    </div>
    <div class="body">
        <div>
            <div class="header">
                <ul>
                    <li class="current">
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="recipes.php">Recipes</a>
                    </li>
                    
                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li><a href="login.php">Login</a></li>';
                        echo '<li><a href="signup.php">Sign Up</a></li>';
                    } else {    
                        echo '<li><form action="logout.php" method="post">
                        <input type="submit" id="logout" name="logout" value="Logout" style="width:100px; color:#000; border:none; padding:5px; font-size:15px;">
                        </form></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="body">
                <!-- Sliding Images Section -->
                <div class="image-slider">
                    <a href="index.php">
                        <img src="images/brinjal.jpg" alt="Image" class="slide-image">

                        <img src="images/loll.jpg" alt="Image" class="slide-image">

                        <img src="images/obba.jpg" alt="Image" class="slide-image">

                        <img src="images/rava.jpg" alt="Image" class="slide-image">

                        <img src="images/rotti.jpg" alt="Image" class="slide-image">
                    </a>
                    
                </div>
            </div>

            <div class="footer">
                <ul>
                    <li>
                        <h2><a href="recipes.php">Recipes</a></h2>
                        <a href="recipes.php"><img src="images/a-z.jpg" alt="Image"></a>
                    </li>
                
                    <li>
                        <h2><a href="videos.php">Videos</a></h2>
                        <a href="videos.php" style="width: 100%; height: auto; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <img src="images/image.png" alt="Image" style="width: 100%; height: auto; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        </a>
                    </li>
                </ul>
                <ul>
                    <div>
                        
                    </div>
                </ul>
            </div>

            <div class="footer">
                <p></p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    let currentIndex = 0;
    const images = document.querySelectorAll('.slide-image');
    const totalImages = images.length;
    
    function slideImages() {
        images.forEach(image => {
            image.style.display = 'none';
            image.classList.remove('active');
        });
        
        images[currentIndex].style.display = 'block';
        images[currentIndex].classList.add('active');
        
        currentIndex = (currentIndex + 1) % totalImages;
    }

    slideImages();
    setInterval(slideImages, 2500); // Changed to 2.5 seconds as requested
});
</script>
</div>
</body>
</html>
