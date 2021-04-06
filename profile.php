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
function getItems(){
    include("config.php");
    $get_all = "select * from items_listing where item_status = 0 and user_user_id =" . $_SESSION['user_id'];
    $run_all = mysqli_query($link, $get_all);
    $x = 0;
    while ($row_all = mysqli_fetch_array($run_all)) {
        $x++;
        $item_id = $row_all['item_id'];
        $item_name = $row_all['item_name'];
        $date_added = $row_all['date_added'];
        $item_status = $row_all['item_status'];
        $item_price = $row_all['item_price'];
        $item_image = $row_all['item_image'];
        $description = $row_all['description'];
        $minutes = (time() - strtotime($date_added)) / 60;
        $history = "";
        
        $test = convertTime($minutes);
        if ($test < 1) {
            $history = "Less than an hour ago";
        } else if ($test < 24) {
            $history = "$test hours ago";
        } else if ($test >= 24){
            $day = $test / 24;
            if ($day < 2) {
                $history = "1 day ago";
            } else if ($day < 7) {
                $day = number_format($day);
                $history = "$day days ago";
            } else {
                $history = "More than 7 days ago";
            }
        }
            echo
            "
            <div class = 'col-sm-4'>
                <a style ='text-decoration:none;'href = '/marketitem.php?item_id=$item_id'>
                    <div class = 'product'>
                        <img style='max-height: 250px;' class = 'img-fluid' src = 'images/market/$item_image' alt = 'Product $x'>
                            <div style='bottom:0'class = 'text'>
                                <h3>$item_name</h3>
                                <p style='text-align:left;'>$description</p>
                                <p style='text-align:left;'>$$item_price</p>
                                <p style='text-align:left;'>$history</p> 
                            </div>
                    </div>
                </a>
            </div>";

    }
}
function getChats() {
    include("config.php");
    $get_all_chat = "select * from item_chat,user,items_listing where (seller_id= " . $_SESSION['user_id'] . " or buyer_id= " . $_SESSION['user_id'] . ")  and user_id=buyer_id and item_chat.item_id=items_listing.item_id";
    $run_all_chat = mysqli_query($link, $get_all_chat);
    $x = 0;
    while ($row_all_chat = mysqli_fetch_array($run_all_chat)) {
        $x++;
        $chat_id = $row_all_chat['chat_id'];
        $buyer_id = $row_all_chat['buyer_id'];
        $buyer_name = $row_all_chat['username'];
        $item_name = $row_all_chat['item_name'];
        $item_id = $row_all_chat['item_id'];
        $seller_id = $row_all_chat['seller_id'];

        echo
        "
            <div>
                <form method='post' action='chat2.php'>
                    <a href='./chat2.php'>
                        <input type='hidden' name='chat_id' value=$chat_id>
                        <input type='hidden' name='seller_id' value=$seller_id>
                        <input type='hidden' name='item_id' value=$item_id>
                        <input type='submit' role='button' value='Chat $chat_id with $buyer_name about $item_name.'/>
                    </a>
                </form>
            </div>";
    }
}

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
            $get_all_chat = "select * from item_chat where (seller_id= " . $_SESSION['user_id'] . " and item_id=$offer_item_id)";
            $run_all_chat = mysqli_query($link, $get_all_chat);
            $row_all_chat = mysqli_fetch_array($run_all_chat);
            $chat_id = $row_all_chat['chat_id'];
            $buyer_id = $row_all_chat['buyer_id'];
            $item_id = $row_all_chat['item_id'];
            $seller_id = $row_all_chat['seller_id'];

            $viewChat = 
            "
                        <form method='post' action='chat2.php'>
                            <a href='./chat2.php'>
                                <input type='hidden' name='chat_id' value=$chat_id>
                                <input type='hidden' name='seller_id' value=$seller_id>
                                <input type='hidden' name='item_id' value=$item_id>
                                <input type='submit' role='button' value='View Chat'/>
                            </a>
                        </form>";
            /*$viewChat = "<p class = 'button'>
  <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
  </p>";*/
            $acceptOrDeclineOrDelete = "<p class = 'button'>
  <a href = '/offer_action.php?item_id=$offer_item_id&action=accept'>Accept</a>
  <a href = '/offer_action.php?item_id=$offer_item_id&action=decline'>Decline</a>
  </p>";
        } elseif ($typeofoffer == "pending") {
            $english1 = "to";
            $english2 = "Sent";
            echo $offer_item_id;
            $get_all_chat = "select * from item_chat where (buyer_id= " . $_SESSION['user_id'] . " and item_id=$offer_item_id)";
            $run_all_chat = mysqli_query($link, $get_all_chat);
            $row_all_chat = mysqli_fetch_array($run_all_chat);
            $chat_id = $row_all_chat['chat_id'];
            $buyer_id = $row_all_chat['buyer_id'];
            $item_id = $row_all_chat['item_id'];
            $seller_id = $row_all_chat['seller_id'];

            $viewChat = 
            "
                        <form method='post' action='chat2.php'>
                            <a href='./chat2.php'>
                                <input type='hidden' name='chat_id' value=$chat_id>
                                <input type='hidden' name='seller_id' value=$seller_id>
                                <input type='hidden' name='item_id' value=$item_id>
                                <input type='submit' role='button' value='View Chat'/>
                            </a>
                        </form>";

            /*$viewChat = "<p class = 'button'>
  <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
  </p>";*/
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
                        <h4>My Items being sold</h4>
                        <?php
                        getItems();
                        ?>
                    </div>
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