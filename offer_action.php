<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

include("config.php");

// if ?item_id=x is not set
if (!isset($_GET['action']) || !isset($_GET['item_id'])) {
    goBackToProfile();
}

if (isset($_GET['action']) && isset($_GET['item_id'])) {
    $action = sanitize_input($_GET['action']);
    $item_id = sanitize_input($_GET['item_id']);
    if ($action !== "accept" && $action !== "decline" && $action !== "cancel" && $action !== "remove")
    {
        goBackToProfile();
    }
    if ($action == "accept" or $action == "decline")
    {
        $get_item = "select offer_id from item_offer where offer_item_id=? and offer_seller_id=?";
    }
    elseif($action == "cancel" or $action == "remove")
    {
        $get_item = "select offer_id from item_offer where offer_item_id=? and offer_buyer_id=?";
    }
    
    $run_item = mysqli_prepare($link, $get_item);
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($run_item, "ss", $param_item_id, $param_user_id);
    // Set parameters
    $param_item_id = $item_id;
    $param_user_id = $_SESSION['user_id'];
    $retrieving_err = "";

    if (mysqli_stmt_execute($run_item)) {
        // Store result
        mysqli_stmt_store_result($run_item);
        // if no results returned
        if (!mysqli_stmt_num_rows($run_item) > 0) {
            mysqli_stmt_close($run_item);
            mysqli_close($link);
            goBackToProfile();
        }

        // Bind result variables
        mysqli_stmt_bind_result($run_item, $offer_id);
        mysqli_stmt_fetch($run_item);
        
        
        if ($action == "accept")
        {
            // set item_status in items_listing to 1
            $query_action = "update items_listing set item_status = 1 where item_id = $item_id";
            $successMessage = "Successfully accepted offer!";
        }
        elseif ($action == "decline")
        {
            $query_action = "update item_offer set offer_status = 1 where offer_id = $offer_id";
            $successMessage = "Successfully declined offer!";
        }
        elseif ($action == "cancel")
        {
            $query_action = "delete from item_offer where offer_id =$offer_id and offer_status=0";
            $successMessage = "Successfully canceled offer request!";
        }
        elseif ($action == "remove")
        {
            $query_action = "delete from item_offer where offer_id = $offer_id and offer_status = 1";
            $successMessage = "Successfully removed this rejected offer from history!";
        }
        if ($link->query($query_action) === FALSE)
        {
            mysqli_stmt_close($run_item);
            mysqli_close($link);
            goBackToProfile();
        }
        
        if ($action == "accept")
        {
            // remove item_offer
            $query2_action = "delete from item_offer where offer_id = $offer_id";
            if ($link->query($query2_action) === FALSE)
            {
                echo $link->error;
                die;
                mysqli_stmt_close($run_item);
                mysqli_close($link);
                goBackToProfile();  
            }
        }
        if ($action == "decline")
        {
            // update date
            $query2_action = "update item_offer set offer_date = NOW() where offer_id = $offer_id";
            if ($link->query($query2_action) === FALSE)
            {
                mysqli_stmt_close($run_item);
                mysqli_close($link);
                goBackToProfile();  
            }
        }
        
        mysqli_stmt_close($run_item);
        mysqli_close($link);
        $_SESSION['message'] = $successMessage;
        header("Location: success.php");
    } else {
        $retrieving_err = "An unexpected error occured";
    }
    
}

function sanitize_input($data) {
    // remove extra characters like whitespaces,tabs
    $data = trim($data);
    // remove slashes
    $data = stripslashes($data);
    // remove < and >
    $data = htmlspecialchars($data);
    return $data;
}

function goBackToProfile() {
    //redirects user to market.php 
    header("Location: profile.php");
    // http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx
    die();
}
?>
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
        <link rel="stylesheet" 
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" >

        <!-- JavaScript Bundle with Popper -->
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js">
        </script>
    </head>
    <body>
        <main class = "main">
            <?php
            include "nav.inc.php";
            ?>
            <section class="section">
                <?php
                // if there is an error, display it
                if (!empty($retrieving_err)) {
                    echo '<span class="help-block">' . $retrieving_err . '</span>';
                }
                ?>
            </section>
        </main>
    </body>
</html>