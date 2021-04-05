<?php
require_once "config.php";
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$status = '';
$profile_picture = '';

// Prepare a select statement
$sql = "SELECT profile_picture, user_verified FROM user WHERE username = ? AND user_verified = 1";

$stmt = mysqli_prepare($link, $sql);
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "s", $param_username);
// Set parameters
$param_username = $_SESSION["username"];

// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)) {
    /* store result */
    mysqli_stmt_store_result($stmt);
    
    if(mysqli_stmt_num_rows($stmt) == 1){
        
        mysqli_stmt_bind_result($stmt, $profile_picture, $user_verified);
        
            if(mysqli_stmt_fetch($stmt)){
                
                if ($user_verified == 1) {
                    $status = 'Verified';
                } else {
                $status = 'Unverified';
                }
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

// Close statement
mysqli_stmt_close($stmt);

 // Prepare a select statement
$sql1 = "SELECT profile_picture FROM user WHERE username = ?";

$stmt1 = mysqli_prepare($link, $sql);
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt1, "s", $param_username);
// Set parameters
$param_username = $_SESSION["username"];

// Attempt to execute the prepared statement
if (mysqli_stmt_execute($stmt1)) {
    /* store result */
    mysqli_stmt_store_result($stmt1);

    if (mysqli_stmt_num_rows($stmt1) == 1) {
        $status = 'Verified';
    } else {
        $status = 'Unverified';
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
}

// Close statement
mysqli_stmt_close($stmt1);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <?php
        include "header.inc.php";
        ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body{ font: 14px sans-serif; text-align: center; }
        </style>
    </head>
    <body>
        <main class = "main">
            <?php
            include "nav.inc.php";
            ?>
            <section id="mainContent" class = "section">
                <div>
                    <img style='display:block; width:100px;height:100px;' src='images/profile/<?php echo $profile_picture;?>' />
                    
                </div>
            <div class="page-header">
                <div>
                    <form action="profile_picture_process.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="fileToUpload" id="fileToUpload"><br>
                            <input type="submit" value="Upload image" name="submit">
                    </form> 
                </div>
                <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
                Account Status: <?php echo $status; ?>
            <?php
            if ($status == 'Unverified') {
            ?>
                <br><a href="email_resend.php" class="btn btn-warning">Resend verification Email.</a>
            <?php
            }
            ?>
            </div>
            <p>
                <a href="reset_password.php" class="btn btn-warning">Reset Your Password</a>
                <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
            </p>
            </section>
<?php
include "footer.inc.php";
?>
        </main>
    </body>
</html>