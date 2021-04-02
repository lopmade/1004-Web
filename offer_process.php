<?php

if (count(get_included_files()) == 1) {
    header("Location: market.php");
    exit();
}


// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.alert('Please login first!')
           window.location.href='login.php';
       </SCRIPT>
       <NOSCRIPT>
           <a href='login.php'>Please login first. Click here if you are not redirected.</a>
       </NOSCRIPT>");
    exit();
}

// Define variables and initialize with empty values
$itemName = $desc = $itemPrice = "";
$itemUpload_err = $reCaptcha_err = $imageUpload_err = $itemName_err = $desc_err = $itemPrice_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // make item_offer table with buyer_id = $_SESSION['user_id'], seller_id = $user_user_id, offer_item_id = $item_id, status =0 , where 1 = denied for tracking
    // in profile make pending offer and accepted offer
    $_SESSION['message'] = "Offer successfully made for item $item_name";
    header("location: success.php");
    
}


?>