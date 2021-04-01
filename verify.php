<?php

require_once "config.php";
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
$token = $_GET['token'];
$email = $_GET['email'];

if(isset($email) && !empty($email) AND isset($token) && !empty($token)){
    // Prepare a select statement
        $sql = "SELECT user_verified FROM user WHERE token = ? AND user_verified = 1";
        
        $stmt = mysqli_prepare($link, $sql);
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $token);
        // Set parameters
        $token = $_GET['token'];
        if(mysqli_stmt_num_rows($stmt) == 1){
            echo "This account has already been verified. Redirecting you back to home page.";
            header("Refresh: 5; url=index.php");
        } else{
            $sql = "UPDATE user SET user_verified = 1 WHERE email = ?";
        
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
            
                // Set parameters
                $param_email = $email;
                if(mysqli_stmt_execute($stmt)){
                    echo "Account verified! Redirecting you to home page in 5 seconds.";
                    header("Refresh: 5; url=index.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                
                
            } else{
                echo "Oops! Something went wrong. Please try again later or contact support.";
            }
            
            
        }

} else {
    echo "Invalid parameters provided for account verification!";
    header("Refresh: 5; url=index.php");
}


?>