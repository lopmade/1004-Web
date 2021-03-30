<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js">
        </script>

        <!--jQuery-->
        <script defer src="js/jquery.min.js"></script>

        <!-- Custom JS -->
        <script defer src="js/main.js"></script>
        
        <!-- font awesome 5 -->
        <script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
        
    </head>
    <body>
        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>
            
            <section class = "section">
                <header>
                    <h1>Welcome to Forum for Selling items</h1>
                    <h2>Home of trading</h2>
                    <i class="fas fa-question-circle"></i>
                </header>

            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
