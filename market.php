
<?php
function getAll(){
    include("config.php");
    $get_all = "select * from items_listing";
    $run_all = mysqli_query($link, $get_all);
    
    
    while($row_all=mysqli_fetch_array($run_all)){
        $item_id = $row_all['item_id'];
        $user_user_id = $row_all['user_user_id'];
        $item_name = $row_all['item_name'];
        $description = $row_all['description'];
        $date_added = $row_all['date_added'];
        $item_status = $row_all['item_status'];
        $item_price = $row_all['item_price'];
        
        $get_item_id = "select * from item_image where item_id = $item_id";
        $run_item_id = mysqli_query($link, $get_item_id);
        $row_item_id = mysqli_fetch_array($run_item_id);
        $item_id_image = $row_item_id['image'];

        echo "<h1>$item_name</h1>";
        echo "<h1>$date_added</h1>";
        echo "<h1>$$item_price </h1>";
        echo "<a href='/marketitem.php?item_id=$item_id'><img src=images/market/$item_id_image></a>";
        
        
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
        <title>Pokemart - Your one stop marketplace</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- universal css --> 
        <link rel="stylesheet" 
              href="css/main.css">
        
        <!-- bootstrap -->
        <link rel="stylesheet" 
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" >
        
        <!-- JavaScript Bundle with Popper -->
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js">
        </script>

        <!--jQuery-->
        <script defer src="js/jquery.min.js"></script>

        <!-- Custom JS -->
        <script defer src="js/main.js"></script>
        
        <!-- font awesome 5 -->
        <script defer src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
        
    </head>
    <body>
        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>
            
            <section class = "section">
                <header>
                    <h1>Market</h1>
                    <h2>Home of trading</h2>
                    <i class="fas fa-question-circle"></i>
                </header>

            </section>
            <div>
                <?php getAll();
                ?>
            </div>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
