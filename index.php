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

            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
