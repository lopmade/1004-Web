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
        <link rel="stylesheet" 
              href="css/slideshow.css">

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

        <script>
            var slideIndex = 0;
            showSlides();

            function showSlides() {
                var i;
                var slides = document.getElementsByClassName("mySlides");
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slideIndex++;
                if (slideIndex > slides.length) {
                    slideIndex = 1
                }
                slides[slideIndex - 1].style.display = "block";
                setTimeout(showSlides, 2000); // Change image every 2 seconds
            }
        </script>
    </head>
    <body>
        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>

            <section id="mainContent" class = "section">
                <h1>Welcome to Forum for Selling items</h1>

                <!-- Slideshow container -->
                <div class="slideshow-container">

                    <!-- Full-width images with number and caption text -->
                    <div class="mySlides fade">
                        <div class="numbertext">1 / 3</div>
                        <img src="/images/pokemonbg.jpg" style="width:100%">
                        <div class="text">Caption Text</div>
                    </div>

                    <div class="mySlides fade">
                        <div class="numbertext">2 / 3</div>
                        <img src="/images/pokemonbg2.jpg" style="width:100%">
                        <div class="text">Caption Two</div>
                    </div>

                    <div class="mySlides fade">
                        <div class="numbertext">3 / 3</div>
                        <img src="/images/pokemonbg3.jpg" style="width:100%">
                        <div class="text">Caption Three</div>
                    </div>

                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>
                </div>
                <br>

                <!-- The dots/circles -->
                <div style="text-align:center">
                    <span class="dot" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>

            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>
