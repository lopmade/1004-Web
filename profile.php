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
if (mysqli_stmt_execute($stmt)) {
    /* store result */
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {

        mysqli_stmt_bind_result($stmt, $profile_picture, $user_verified);

        if (mysqli_stmt_fetch($stmt)) {

            if (empty($profile_picture)) {
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

function convertTime($time) {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($hours);
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION['user_id'] = "";
}

function getItems() {
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
        } else if ($test >= 24) {
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
                                <h2>$item_name</h2>
                                <p style='text-align:left;'>$description</p>
                                <p style='text-align:left;'>$$item_price</p>
                                <p style='text-align:left;'>$history</p> 
                            </div>
                            <a class='btn btn-primary' href='update.php?id=$item_id' role='button'>Edit</a>
                            <a class='btn btn-primary' href='delete.php?id=$item_id&image=$item_image' name='delete' role='button'>Delete</a>
                    </div>
                </a>
            </div>";
    }
    if ($x==0){
        echo "<div class = 'col-sm-4'>---No items being sold. Use Add Item to start selling---</div>";
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
            $item_name = $row_all['item_name'];

            $viewChat = "
                        <form method='post' action='chat2.php'>
                            <a href='./chat2.php'>
                                <input type='hidden' name='chat_id' value=$chat_id>
                                <input type='hidden' name='seller_id' value=$seller_id>
                                <input type='hidden' name='item_id' value=$item_id>
                                <input type='submit' role='button' class='btn btn-primary' value='View Chat'/>
                            </a>
                        </form>";
            /* $viewChat = "<p class = 'button'>
              <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
              </p>"; */
            $acceptOrDeclineOrDelete = "<p class = 'button'>
  <a href = '/offer_action.php?item_id=$offer_item_id&action=accept'>Accept</a>
  <a href = '/offer_action.php?item_id=$offer_item_id&action=decline'>Decline</a>
  </p>";
        } elseif ($typeofoffer == "pending") {
            $english1 = "to";
            $english2 = "Sent";
            $get_all_chat = "select * from item_chat where (buyer_id= " . $_SESSION['user_id'] . " and item_id=$offer_item_id)";
            $run_all_chat = mysqli_query($link, $get_all_chat);
            $row_all_chat = mysqli_fetch_array($run_all_chat);
            $chat_id = $row_all_chat['chat_id'];
            $buyer_id = $row_all_chat['buyer_id'];
            $item_id = $row_all_chat['item_id'];
            $seller_id = $row_all_chat['seller_id'];

            $viewChat = "
                        <form method='post' action='chat2.php'>
                            <a href='./chat2.php'>
                                <input type='hidden' name='chat_id' value=$chat_id>
                                <input type='hidden' name='seller_id' value=$seller_id>
                                <input type='hidden' name='item_id' value=$item_id>
                                <input type='submit' role='button' class='btn btn-primary' value='View Chat'/>
                            </a>
                        </form>";

            /* $viewChat = "<p class = 'button'>
              <a href = '/marketitem.php?item_id=$offer_item_id'>View Chat</a>
              </p>"; */
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
  <h2>
  <a href = '/marketitem.php?item_id=$offer_item_id'>
  $item_name
  </a>
  </h2>
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
                <div id='propicverify' class="container-fluid">
                    <img style='display:block; width:100px;height:100px;border-radius:30%;' src='images/profile/<?php echo $profile_picture; ?>' />
                    <div class="row">
                        <?php echo "<h1>" . htmlspecialchars($_SESSION["username"]) . "</h1>"; ?>
                        <form action="profile_picture_process.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
                            <input type="submit" value="Update Profile Picture" name="submit">
                        </form> 
                    </div>
                    <br><br>
                    <div class="row">
                        <h2 class="ml-2">Account Status: <?php echo "<b>" . $status . "</b>"; ?></h2>
                    </div>
                </div>
                
                <?php
                if ($status == 'Unverified') {
                    ?>
                    <div id='verifybutton' class="container-fluid">
                        <a href="email_resend.php" class="btn btn-warning">Resend verification Email.</a>
                    </div>
                    <?php
                }
                ?>
                <br>
                <div id ="content" class="container-fluid">
                    <div class="row">
                        <h3>My Items</h3>
                        <?php
                        getItems();
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class="row">
                        <h3>My Current Incoming Offers</h3>
                        <?php
                        getOffers("incoming");
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class ="row">
                        <h3>My Sent and Pending Offers</h3>
                        <?php
                        getOffers("pending");
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <div class ="row">
                        <h3>My Sent and Rejected Offers</h3>
                        <?php
                        getOffers("rejected");
                        ?>
                    </div>
                </div>
                <div id ="content" class="container-fluid">
                    <br>
                    <a href="reset_password.php" class="btn btn-warning">Reset Your Password</a>
                    <br><br>
                    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>

                </div>

            </section>
            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>