<?php
error_reporting(0);
include("../connection/connect.php");

// Delete recipe if `id` is set in GET
if (isset($_GET['id'])) {
    $sql = "DELETE FROM full_recipy WHERE rid='" . mysqli_real_escape_string($db, $_GET['id']) . "'";
    mysqli_query($db, $sql);
}

// Initialize feedback messages
$feedback = "";

if (isset($_POST['submit'])) { // Check if the form is submitted
    $title = $_POST['title'];
    $rtext = $_POST['rtext'];
    $ing = $_POST['ing'];
    $disc = $_POST['disc'];
    $rid = $_POST['rid'];

    // File handling
    $file = $_FILES['file'];
    $fname = $file['name'];
    $temp = $file['tmp_name'];
    $fsize = $file['size'];
    $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
    $fnew = uniqid() . '.' . $extension;
    $store = "img/" . basename($fnew);

    // Validation
    if (empty($title) || empty($rtext) || empty($ing) || empty($disc) || empty($rid) || empty($fname)) {
        $feedback = '<div class="alert alert-error alert-block">
                        <a class="close" data-dismiss="alert" href="#">&times;</a>
                        <h4 class="alert-heading">Error!</h4>
                        All fields must be filled.
                     </div>';
    } elseif (!in_array($extension, ['jpg', 'png', 'gif'])) {
        $feedback = '<div class="alert alert-error alert-block">
                        <a class="close" data-dismiss="alert" href="#">&times;</a>
                        <h4 class="alert-heading">Error!</h4>
                        Invalid file type. Only JPG, PNG, and GIF are allowed.
                     </div>';
    } elseif ($fsize >= 1000000) {
        $feedback = '<div class="alert alert-error alert-block">
                        <a class="close" data-dismiss="alert" href="#">&times;</a>
                        <h4 class="alert-heading">Error!</h4>
                        Maximum upload size is 1MB.
                     </div>';
    } else {
        // Move the uploaded file and insert data into the database
        if (move_uploaded_file($temp, $store)) {
            $sql = "INSERT INTO full_recipy (title, title_text, image, ing_text, disc, rid) 
                    VALUES (
                        '" . mysqli_real_escape_string($db, $title) . "',
                        '" . mysqli_real_escape_string($db, $rtext) . "',
                        '" . mysqli_real_escape_string($db, $fnew) . "',
                        '" . mysqli_real_escape_string($db, $ing) . "',
                        '" . mysqli_real_escape_string($db, $disc) . "',
                        '" . mysqli_real_escape_string($db, $rid) . "'
                    )";
            if (mysqli_query($db, $sql)) {
                $feedback = '<div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Success</h4>
                                The record has been added successfully.
                             </div>';
            } else {
                $feedback = '<div class="alert alert-error alert-block">
                                <a class="close" data-dismiss="alert" href="#">&times;</a>
                                <h4 class="alert-heading">Error!</h4>
                                Failed to insert record into the database.
                             </div>';
            }
        } else {
            $feedback = '<div class="alert alert-error alert-block">
                            <a class="close" data-dismiss="alert" href="#">&times;</a>
                            <h4 class="alert-heading">Error!</h4>
                            File upload failed.
                         </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html class="no-js">
<head>
    <title>Admin Home Page</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" media="screen">
    <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="#">Admin Panel</a>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> Sudeep Kantigonda <i class="caret"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="#">Profile</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="index.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3" id="sidebar">
                <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
                    <li><a href="dashboard.php"><i class="icon-chevron-right"></i> Dashboard</a></li>
                    <li><a href="recipes.php"><i class="icon-chevron-right"></i> Recipes</a></li>
                    <li class="active"><a href="detail.php"><i class="icon-chevron-right"></i> Detail Recipes</a></li>
                    <li><a href="users.php"><i class="icon-chevron-right"></i> Users</a></li>
                    <li><a href="comment.php"><i class="icon-chevron-right"></i> Comments</a></li>
                </ul>
            </div>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?= $feedback; ?>
                </div>

                <div class="row-fluid">
                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Detail Recipes Table</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Title Text</th>
                                            <th>Image</th>
                                            <th>Ingredients</th>
                                            <th>Description</th>
                                            <th>Recipe ID</th>
                                            <th>Operations</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM full_recipy ORDER BY id DESC";
                                        $result = mysqli_query($db, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['title']}</td>
                                                <td>{$row['title_text']}</td>
                                                <td>{$row['image']}</td>
                                                <td>{$row['ing_text']}</td>
                                                <td>{$row['disc']}</td>
                                                <td>{$row['rid']}</td>
                                                <td>
                                                    <a class='btn btn-danger' href='detail.php?id={$row['rid']}'>
                                                        <i class='icon-remove icon-white'></i> Delete
                                                    </a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left">Add a New Record</div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                        <legend>Add Recipes in Detail</legend>
                                        <div class="control-group">
                                            <label class="control-label" for="typeahead">Recipe Title</label>
                                            <div class="controls">
                                                <input type="text" class="span6" name="title" id="typeahead">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="textarea2">Recipe Text</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge" name="rtext" style="width: 810px; height: 200px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="fileInput">File Input</label>
                                            <div class="controls">
                                                <input class="input-file uniform_on" id="fileInput" type="file" name="file">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="textarea2">Ingredients</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge" name="ing" style="width: 810px; height: 200px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="textarea2">Description</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge" name="disc" style="width: 810px; height: 200px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="typeahead">Recipe ID</label>
                                            <div class="controls">
                                                <input type="text" class="span6" name="rid" id="typeahead">
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="submit" class="btn btn-primary">Add Recipe</button>
                                            <button type="reset" class="btn">Cancel</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendors/jquery-1.9.1.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
