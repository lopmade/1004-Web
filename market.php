
<?php

function getAll() {
    include("config.php");
    $get_all = "select * from items_listing";
    $run_all = mysqli_query($link, $get_all);


    while ($row_all = mysqli_fetch_array($run_all)) {
        $item_id = $row_all['item_id'];
        $user_user_id = $row_all['user_user_id'];
        $item_name = $row_all['item_name'];
        $description = $row_all['description'];
        $date_added = $row_all['date_added'];
        $item_status = $row_all['item_status'];
        $item_price = $row_all['item_price'];
        $item_image = $row_all['item_image'];
        /*
          $get_item_id = "select * from item_image where item_id = $item_id";
          $run_item_id = mysqli_query($link, $get_item_id);
          $row_item_id = mysqli_fetch_array($run_item_id);
          $item_id_image = $row_item_id['image'];
         */
        echo "<h1>$item_name</h1>";
        echo "<h1>$date_added</h1>";
        echo "<h1>$$item_price </h1>";
        echo "<a href='/marketitem.php?item_id=$item_id'><img src=images/market/$item_image></a>";
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

            <section id="mainContent" class = "section">
                <h1>Market</h1>
                <div>
                    <?php
                    getAll();
                    ?>
                </div>

            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
