<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
unset($_SESSION["loggedin"]);
unset($_SESSION["user_id"]);
unset($_SESSION["username"]);
unset($_SESSION["email"]);
unset($_SESSION["token"]);
unset($_SESSION["first_name"]);
unset($_SESSION["last_name"]);
unset($_SESSION["profile_picture"]);
unset($_SESSION["user_verified"]);

// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>