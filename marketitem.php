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
    $get_item = "select * from items_listing where item_id='$item_id'";
    $run_item = mysqli_query($link, $get_item);
    // if the item_id does not exist
    if (!mysqli_num_rows($run_item) > 0) {
        goBackToMarket();
    }
    $row_item = mysqli_fetch_array($run_item);
    $user_user_id = $row_item['user_user_id'];
    $item_name = $row_item['item_name'];
    $description = $row_item['description'];
    $date_added = $row_item['date_added'];
    $item_status = $row_item['item_status'];
    // if the item is sold already
    if ($item_status === 1) {
        goBackToMarket();
    }
    
    $get_user_user_id = "select username from user where user_id = $user_user_id";
    $run_user_user_id = mysqli_query($link,$get_user_user_id);
    $row_user_user_id = mysqli_fetch_array($run_user_user_id);
    $user_user_id_username = $row_user_user_id['username'];
    
    
    // can only display one image for now might need to implement into a function with loop in the future to display more
    
    $get_item_id = "select * from item_image where item_id = $item_id";
    $run_item_id = mysqli_query($link, $get_item_id);
    $row_item_id = mysqli_fetch_array($run_item_id);
    $item_id_image = $row_item_id['image'];
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
                <h1>USER ID:<?php echo $user_user_id; ?></h1>
                <h2>USERNAME:<?php echo $user_user_id_username; ?></h2>
                <h1>ITEM NAME:<?php echo $item_name; ?></h1>
                <h1>DESCRIPTION:<?php echo $description; ?></h1>
                <h1>DATE ADDED:<?php echo $date_added; ?></h1>
                <!--can only display one image at the time for now ,need carousell or something in the future --> 
                <img src="images/market/<?php echo $item_id_image ?>" >
            </section>
        </main>
    </body>
</html>
