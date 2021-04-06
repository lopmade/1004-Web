<?php
require_once "config.php";
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
$status = '';
$profile_picture = '';

// Prepare a select statement
$sql = "SELECT profile_picture, user_verified FROM user WHERE username = ?";

$stmt = mysqli_prepare($link, $sql);
// Bind variables to the prepared statement as parameters
mysqli_stmt_bind_param($stmt, "s", $param_username);
// Set parameters
$param_username = $_SESSION["username"];

// Attempt to execute the prepared statement
if(mysqli_stmt_execute($stmt)) {
    /* store result */
    mysqli_stmt_store_result($stmt);
    
    if(mysqli_stmt_num_rows($stmt) == 1){
        
        mysqli_stmt_bind_result($stmt, $profile_picture, $user_verified);
        
            if(mysqli_stmt_fetch($stmt)){
                
                if(empty($profile_picture)){
                    $profile_picture = 'default.png';
                }
                if ($user_verified == 1) {
                    $status = 'Verified';
                } else {
                    $status = 'Unverified';
                }
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

// Close statement
mysqli_stmt_close($stmt);

function getOffers($typeofoffer) {
    include("config.php");

    //require_once("config.php");

    if ($typeofoffer == "incoming") {
        // get from status = 0 (where not rejected yet)
        $get_all_offer = "select * from item_offer where offer_seller_id= " . $_SESSION['user_id'] . " and offer_status = 0";
    } elseif ($typeofoffer == "pending") {
        $get_all_offer = "select * from item_offer where offer_buyer_id= " . $_SESSION['user_id'] . " and offer_status = 0";
    } elseif ($typeofoffer == "rejected") {
        // offer_status 1 = rejected
        $get_all_offer = "select * from item_offer where offer_buyer_id= " . $_SESSION['user_id'] . " and offer_status = 1";
    }

    $run_all_offer = mysqli_query($link, $get_all_offer);
    if (mysqli_num_rows($run_all_offer) == 0) {
        echo "<div class = 'col-sm-4'>---No offers found---</div>";
        return;
    }
    $x = 0;
    while ($row_all = mysqli_fetch_array($run_all_offer)) {

        $x++;
        $offer_item_id = $row_all['offer_item_id'];
        if ($typeofoffer == "incoming") {
            $offer_user_id = $row_all['offer_buyer_id'];
        } elseif ($typeofoffer == "pending" || $typeofoffer == "rejected") {
            $offer_user_id = $row_all['offer_seller_id'];
        }
        $offer_date = $row_all['offer_date'];

        // get each item name and image where is not sold yet
        $get_offer_item = "select item_name,item_image from items_listing where item_id = " . $offer_item_id;
        $run_offer_item = mysqli_query($link, $get_offer_item);
        $row_offer_item = mysqli_fetch_array($run_offer_item);
        $item_name = $row_offer_item['item_name'];
        $item_image = $row_offer_item['item_image'];

        $get_offer_item_user_name = "select username from user where user_id = " . $offer_user_id;
        $run_offer_item_user_name = mysqli_query($link, $get_offer_item_user_name);
        $row_offer_item_user_name = mysqli_fetch_array($run_offer_item_user_name);
        $user_name = $row_offer_item_user_name['username'];

        if ($typeofoffer == "incoming") {
            $english1 = "by";
            $english2 = "Received";
            $viewChat = "<p class = 'button'>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
                        </p>";
            $acceptOrDeclineOrDelete = "<p class = 'button'>
                            <a href = '/offer_action.php?item_id=$offer_item_id&action=accept'>Accept</a>
                            <a href = '/offer_action.php?item_id=$offer_item_id&action=decline'>Decline</a>
                        </p>";
        } elseif ($typeofoffer == "pending") {
            $english1 = "to";
            $english2 = "Sent";
            $viewChat = "<p class = 'button'>
                            <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
                        </p>";
            $acceptOrDeclineOrDelete = "<p class = 'button'>
                            <a href = '/offer_action.php?item_id=$offer_item_id&action=cancel'>Cancel Offer</a>
                        </p>";
        } elseif ($typeofoffer == "rejected") {
            $english1 = "to";
            $english2 = "Rejected";
            $viewChat = "";
            $acceptOrDeclineOrDelete = "<p class = 'button'>
                            <a href = '/offer_action.php?item_id=$offer_item_id&action=remove'>Remove From History</a>
                        </p>";
        }
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
                            Offered " . $english1 . ": $user_name
                        </p>
                        <p class = 'price'>
                            Date of " . $english2 . " Offer: $offer_date
                        </p>
                        $viewChat
                        $acceptOrDeclineOrDelete
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
        <link rel="stylesheet" 
              href="css/market.css">
    </head>
    <body>
        <main class = "main">
            <?php
            include "nav.inc.php";
            ?>
            <section id="mainContent" class = "section">
                <div>
                    <img style='display:block; width:100px;height:100px;' src='images/profile/<?php echo $profile_picture;?>' />
                    
                </div>
            <div class="page-header">
                <div>
                    <form action="profile_picture_process.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="fileToUpload" id="fileToUpload"><br>
                            <input type="submit" value="Upload image" name="submit">
                    </form> 
                </div>
                <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
                Account Status: <?php echo $status; ?>
            <?php
            if ($status == 'Unverified') {
            ?>
                <br><a href="email_resend.php" class="btn btn-warning">Resend verification Email.</a>
            <?php
            }
            ?>
            </div>

                <div id ="content" class="container-fluid">
                    <div class="row">
                        <h4>My Current Incoming Offers</h4>
                        <?php
                        getOffers("incoming");
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class ="row">
                        <h4>My Sent and Pending Offers</h4>
                        <?php
                        getOffers("pending");
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class ="row">
                        <h4>My Sent and Rejected Offers</h4>
                        <?php
                        getOffers("rejected");
                        ?>
                    </div>
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