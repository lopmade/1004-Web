<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <main class="main">
            <?php
            include "nav.inc.php";
            ?>
            <h2>Success</h2> <br>
            <?php
            if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            } else {
                header("location: index.php");
                die();
            }
            ?>
            <br><br>
            <a href="index.php"><button class="btn btn-success">Home</button></a>
            <br><br><br>
        </main>
    </body>
</html>