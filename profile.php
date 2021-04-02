<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

function getIncomingOffers() {
    include("config.php");
    $get_all_offer = "select * from item_offer where offer_seller_id= " . $_SESSION['user_id'] . " and offer_status = 0";
    $run_all_offer = mysqli_query($link, $get_all_offer);
    $x = 0;
    while ($row_all = mysqli_fetch_array($run_all_offer)) {
        $x++;
        $offer_item_id = $row_all['offer_item_id'];
        $offer_buyer_id = $row_all['offer_buyer_id'];
        $offer_date = $row_all['offer_date'];

        // get each item name and image where is not sold yet
        $get_offer_item = "select item_name,item_image from items_listing where item_id = " . $offer_item_id;
        $run_offer_item = mysqli_query($link, $get_offer_item);
        $row_offer_item = mysqli_fetch_array($run_offer_item);
        $item_name = $row_offer_item['item_name'];
        $item_image = $row_offer_item['item_image'];

        $get_offer_item_buyer_name = "select username from user where user_id = " . $offer_buyer_id;
        $run_offer_item_buyer_name = mysqli_query($link, $get_offer_item_buyer_name);
        $row_offer_item_buyer_name = mysqli_fetch_array($run_offer_item_buyer_name);
        $buyer_name = $row_offer_item_buyer_name['username'];
        echo
        "
            <div class = 'col-sm-4'>
                <div class = 'product'>
                    <a href = '/marketitem.php?item_id=$offer_item_id'>
                        <img style='height:300px;' class = 'img-fluid' src = 'images/market/$item_image' alt = 'Product $x'>
                    </a>
                    <div class = 'text'>
                        <h3>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>
                                $item_name
                            </a>
                        </h3>
                        <p class = 'price'>
                            Offered by: $buyer_name
                        </p>
                        <p class = 'price'>
                            Date of Offer: $offer_date
                        </p>
                        <p class = 'button'>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
                        </p>
                        <p class = 'button'>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>Accept</a>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>Decline</a>
                        </p>
                    </div>
                </div>
            </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Pokemart - Profile</title>
        <?php
        include "header.inc.php";
        ?>

    </head>
    <body>

        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>
            <link rel="stylesheet" 
                  href="css/market.css">

            <section id="mainContent" class = "section">
                <div class="page-header">
                    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
                </div>
                <div id ="content" class="container-fluid">
                    <div class="row">
                        <h4>My Current Incoming Offers</h4>
                        <?php
                        getIncomingOffers();
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class ="row">
                        <h4>My Pending Offers</h4>
                        <?php
                        //if ()
                        ?>
                    </div>
                </div>
                <div id ="myRejectedOffers">
                    <h4>My Current Offers</h4>
                    <?php
                    //if ()
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