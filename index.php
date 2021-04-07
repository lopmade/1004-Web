<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
?>
<html>
    <head>
        <title>Pokemart - Your one stop marketplace</title>
        <?php
        include "header.inc.php";
        ?>
        <link rel="stylesheet" href="css/index.css" />
    </head>
    <body>
        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>

            <section id="mainContent" class = "section">
                <h1>How to</h1>
                <div class="row">
                    <div class="column">
                        <div class="card">
                            <img class="img-fluid" src="images/buy.png" alt="Jane" style="width:300px;margin:auto;padding:auto;">
                            <div class="container-fluid">
                                <h2>Buyers</h2>
                                <p class="title">Looking to purchase a product?</p>
                                <p>Step 1: Register/Login</p>
                                <p>Step 2: Click on an item you like</p>
                                <p>Step 3: Make offer</p>
                                <p>Step 4: Go to chat and negotiate</p>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="card">
                            <img class="img-fluid" src="images/sell.png" alt="Mike" style="width:300px;margin:auto;padding:auto;">
                            <div class="container-fluid">
                                <h2>Sellers</h2>
                                <p class="title">Looking to sell a product?</p>
                                <p>Step 1: Register/Login</p>
                                <p>Step 2: Confirm Email</p>
                                <p>Step 3: Upload Item</p>
                                <p>Step 4: Wait for Buyer</p>
                                <p>Step 5 (Seller): Accept/Decline offer</p>
                            </div>
                        </div>
                    </div>
            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
