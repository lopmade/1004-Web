<?php
require_once "config.php";
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

$email = $_GET['email'];
$token = $_GET['token'];
$verify_msg = '';

// If email and hash variables are not empty, proceed 
if (isset($email) && !empty($email) AND isset($token) && !empty($token)) {

    $hash = $connect->escape_string($_GET['token']);

    //Select user with matching email and hash, who arent verified yet (active=0)
    $sql = "SELECT * FROM users WHERE email = ? AND token = ? AND verify = '0' ";

    $stmt = mysqli_prepare($link, $sql);
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_token);
    // Set parameters
    $param_email = $email;
    $param_token = $token;

    if (mysqli_stmt_execute($stmt)) {
        /* store result */
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            echo "Account has already been activated / URL is invalid! Redirecting you back to home page!";
            header("refresh:5; url=index.php");
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            // Prepare an update statement
            $sql = "UPDATE user SET verify = 1 WHERE email = ? AND token = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $email, $token);

                // Set parameters
                $param_email = $email;
                $param_token = $token;
                
                echo "Your account has been verified and activated successfully! Redirecting you back to home page!";
                // Set user status to active (active=1)
                $connect->query("UPDATE user SET verify='1' WHERE email='$email' ") or die($connect->error);
                header("refresh:5; url=index.php");
            
            } else {
                echo "An error occured, please contact admin for support. Redirecting you back to home page!";
                header("refresh:5; url=index.php");
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        $_SESSION['message'] = "Invalid parameters provided for account verification!";
        header("location: error.php");
    }
}
?>
