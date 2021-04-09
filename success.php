<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Success</title>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <main class="main">
            <?php
            include "nav.inc.php";
            ?>
            <section id="mainContent" class = "section">
                <div class="row">
                    <div id='successthing' class="d-flex justify-content-center">
                        <h2>Success</h2>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div id='successthing' class="d-flex justify-content-center">
                        <?php
                        if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        } else {
                            header("location: index.php");
                            die();
                        }
                        ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div id='successthing' class="d-flex justify-content-center">
                        <a href="index.php"><button class="btn btn-success">Home</button></a>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>