<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

include("config.php");

// if ?item_id=x is not set
if (!isset($_GET['item_id'])) {
    goBackToMarket();
}

if (isset($_GET['item_id'])) {
    $item_id = sanitize_input($_GET['item_id']);
    $get_item = "select * from items_listing where item_id=?";
    $run_item = mysqli_prepare($link, $get_item);
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($run_item, "s", $param_item_id);
    // Set parameters
    $param_item_id = $item_id;
    $retrieving_err = "";

    if (mysqli_stmt_execute($run_item)) {
        // Store result
        mysqli_stmt_store_result($run_item);
        // if the item_id does not exist
        if (!mysqli_stmt_num_rows($run_item) > 0) {
            mysqli_stmt_close($run_item);
            mysqli_close($link);
            goBackToMarket();
        }

        // Bind result variables
        mysqli_stmt_bind_result($run_item, $item_id, $user_user_id, $item_name, $description, $date_added, $item_status, $item_price, $item_image);

        if (mysqli_stmt_fetch($run_item)) {
            // if the item is sold already
            if ($item_status === 1) {
                mysqli_stmt_close($run_item);
                mysqli_close($link);
                goBackToMarket();
            }
        }
        $get_user_user_id = "select username from user where user_id = $user_user_id";
        $run_user_user_id = mysqli_query($link, $get_user_user_id);
        $row_user_user_id = mysqli_fetch_array($run_user_user_id);
        $user_user_id_username = $row_user_user_id['username'];
    } else {
        $retrieving_err = "An unexpected error occured";
    }

    // if the item is sold already
    if ($item_status === 1) {
        goBackToMarket();
    }

    mysqli_stmt_close($run_item);
    $chat_id = $item_id . "+" . $_SESSION['user_id'];
    $_SESSION['chat_id'] = $chat_id . "log.html";
    require_once "./offer_process.php";
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

function goBackToMarket() {
    //redirects user to market.php 
    header("Location: market.php");
    // http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx
    die();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <title> Market Item</title>
        <?php
        include "header.inc.php";
        ?>
        <link rel="stylesheet" 
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" >

        <!-- JavaScript Bundle with Popper -->
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js">
        </script>
        <link rel="stylesheet" 
              href="css/marketitem.css">
    </head>
    <body>
        <main class = "main">
            <?php
            include "nav.inc.php";
            ?>
            <section id="mainContent" class = "section">
                <?php
                // if there is an error, display it
                if (!empty($retrieving_err)) {
                    echo '<span class="help-block">' . $retrieving_err . '</span>';
                }
                if (!empty($alreadyOffered_err)) {
                    echo '<h2>' . $alreadyOffered_err . '</h2>';
                }
                ?>
                <h1 style="color:#747474"><?php echo $item_name; ?></h1>
                <div id="content" class="container-fluid">
                    <!--<p>USER ID:<?php echo $user_user_id; ?></p>-->
                    <img alt="item image" class="img-fluid" style="width:200px;" src="images/market/<?php echo $item_image ?>">
                    <b><p>Listed by:  <?php echo $user_user_id_username; ?></p></b>
                    <b><p>Price:  $<?php echo $item_price; ?></p></b>
                    <b><p>Description:    <?php echo $description; ?></p></b>
                    <b><p>Date Added:    <?php echo $date_added; ?></p></b>
                    <!--can only display one image at the time for now ,need carousell or something in the future --> 
                    
                    <!--Only user's can update or delete its own item-->
                    <?php
                    // if the item being viewed is NOT the user's item
                    if ($user_user_id !== $_SESSION['user_id']) {
                        echo "<form action='" . htmlspecialchars(basename($_SERVER['REQUEST_URI'])) . "' method='post'>"
                        . "<input type='submit' id='offerItemBtn' class='btn btn-primary' value='Make Offer' name='submit'>"
                        . "</form>";
                    } else {
                        echo "<h1>This is your item by the way</h1>";
                    }
                    ?>
                </div>




            </section>
            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>