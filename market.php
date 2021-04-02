
<?php

function getAll() {
    include("config.php");
    $get_all = "select * from items_listing";
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
        echo
        "
            <div class = 'col-sm-4'>
                <div class = 'product'>
                    <a href = '/marketitem.php?item_id=$item_id'>
                        <img style='height:300px;' class = 'img-fluid' src = 'images/market/$item_image' alt = 'Product $x'>
                    </a>
                    <div class = 'text'>
                        <h3>
                            <a href = '/marketitem.php?item_id=$item_id'>
                                $item_name
                            </a>
                        </h3>
                        <p class = 'price'>
                            $$item_price
                        </p>
                        <p class = 'button'>
                            <a href = '/marketitem.php?item_id=$item_id'>View Details</a>
                        </p>
                    </div>
                </div>
            </div>";




        /*  TABLE FORM
          echo""
          . "<a href = '/marketitem.php?item_id=$item_id' "
          . "class = 'list-group-item list-group-item-action'>"
          . "<img style = 'width:100px;height:100px;'src = images/market/$item_image>
          <div class = 'd-flex w-100 justify-content-between'>
          <h5 class = 'mb-1'>$item_name</h5>
          <small>$date_added</small>
          </div>
          <p class = 'mb-1'>$description</p>
          <small>Price$$item_price</small>
          </a>
          ";
         */



        /*
          echo "<h1>$item_name</h1>";
          echo "<h1>$date_added</h1>";
          echo "<h1>$$item_price </h1>";
          echo "<a href = '/marketitem.php?item_id=$item_id'><img src = images/market/$item_image></a>";
         */
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
