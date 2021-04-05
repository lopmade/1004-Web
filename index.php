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

    </head>
    <body>
        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>

            <section id="mainContent" class = "section">
                <h1>Welcome to Forum for Selling items</h1>
                <p>Seller</p>
                <p>Step 1: Register/Login</p>
                <p>Step 2: Confirm Email</p>
                <p>Step 3: Upload Item</p>
                <p>Step 4: Wait for Buyer</p>
                <p>Step 5 (Seller): Accept/Decline offer</p>
                <p>Buyer</p>
                <p>Step 1: Register/Login</p>
                <p>Step 2: Click on an item you like</p>
                <p>Step 3: Make offer</p>
                <p>Step 4: Go to chat and negotiate</p>

            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
