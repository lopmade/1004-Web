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

$alreadyOffered_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Prepare a select statement to check if the offer exists already
    $sql = "SELECT offer_buyer_id,offer_seller_id FROM item_offer WHERE offer_buyer_id = ? and offer_item_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $param_offer_buyer_id, $param_offer_item_id);

    // Set parameters
    $param_offer_buyer_id = $_SESSION['user_id'];
    $param_offer_item_id = $item_id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);
        // if an offer has already been made
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $alreadyOffered_err = "Already sent offer";
            //header("Location: ".basename($_SERVER['REQUEST_URI']));
            //exit;
        }
        else
        {
            // Close statement
            mysqli_stmt_close($stmt);
            // Prepare an insert statement
            $sql = "INSERT INTO item_offer (offer_buyer_id,offer_seller_id,offer_item_id,offer_date) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($link, $sql);
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_offer_buyer_id, $param_offer_seller_id,$param_offer_item_id,$param_offer_date);

            // Set parameters
            $param_offer_buyer_id = $_SESSION['user_id'];
            $param_offer_seller_id = $user_user_id;
            $param_offer_date = date("Y-m-d H:i:s");
            $param_offer_item_id = $item_id;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Close statement
                mysqli_stmt_close($stmt);
                // Redirect to success
                $_SESSION['message'] = "Offer successfully made for item $item_name";
                header("location: success.php");
            } else {
                $retrieving_err = "An unexpected error occured";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        
    } else {
        $retrieving_err = "An unexpected error occured";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($link);

    // make item_offer table with buyer_id = $_SESSION['user_id'], seller_id = $user_user_id, offer_item_id = $item_id, status =0 , where 1 = denied for tracking

    // in profile make pending offer and accepted offer
}
?>