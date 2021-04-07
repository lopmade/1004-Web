<?php
if (!isset($_SESSION)) {
    session_start();
}

function convertTime($time) {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($hours);
}
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION['user_id']="";
}
?>


<?php

function getAll() {
    include("config.php");
    $get_all = "select * from items_listing where item_status = 0";
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
        $seller_id= $row_all['user_user_id'];
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
        if ($seller_id != $_SESSION['user_id']){
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
?>



<html>
    <head>
        <title>Pokemart - Marketplace</title>
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
                <h1>Market</h1>

                <!--For table form-->
                <!--<div class="list-group">-->
                <!--</div>-->

                <div id="content" class="container-fluid">
                    <div class = 'row'>
                        <?php
                        getAll();
                        ?>
                    </div>
                </div>
            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
