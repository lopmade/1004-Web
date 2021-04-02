<?php
include("config.php");
?>

<?php
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
        mysqli_stmt_bind_result($run_item, $item_id, $user_user_id, $item_name, $description, $date_added, $item_status,$item_price, $item_image);

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
        
    }
    else 
    {
        $retrieving_err = "An unexpected error occured";
    }
    mysqli_stmt_close($run_item);
    mysqli_close($link);
    
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
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
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
                    die();
                }
                ?>
                <h6>USER ID:<?php echo $user_user_id; ?></h6>
                <h6>USERNAME:<?php echo $user_user_id_username; ?></h6>
                <h6>ITEM NAME:<?php echo $item_name; ?></h6>
                <h6>PRICE:<?php echo $item_price; ?></h6>
                <h6>DESCRIPTION:<?php echo $description; ?></h6>
                <h6>DATE ADDED:<?php echo $date_added; ?></h6>
                <!--can only display one image at the time for now ,need carousell or something in the future --> 
                <img src="images/market/<?php echo $item_image ?>" >
                <?php
                if ($user_user_id !== $_SESSION['user_id'])
                {
                    echo "<form action=".htmlspecialchars(basename($_SERVER['REQUEST_URI']))." method='post'>"
                            . "<input type='submit' id='offerItemBtn' class='btn btn-primary' value='Make Offer' name='submit'>"
                            . "</form>";
                }
                ?>
            </section>
        </main>
    </body>
</html>
