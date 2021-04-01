<html>
    <head>
        <title>Pokemart - Your one stop marketplace</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- universal css --> 
        <link rel="stylesheet" 
              href="css/main.css">
        <link rel="stylesheet" 
              href="css/about.css">

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
            <section id="mainContent" class = "section">

                <div class="bgimg-1">
                    <div class="caption">
                        <span class="border" style="background-color: rgb(0,0,0);
                              background-color: rgba(0,0,0,0.75);font-size:25px;color:white;">Who are we?</span>
                    </div>
                </div>

                <div style="color:black;background-color:white;box-shadow:black 1px 10px 5px 2px;text-align:center;padding:50px 80px;text-align: justify;">
                    <h3 style="text-align:center;margin-bottom:30px;">Pokemart</h3>
                    <p>
                        Pokemart is our official online store for Pokemon merchandise, focusing on unique designs and high quality products for all ages. Pokémon launched in Japan in 1996 and today is one of the most popular children’s entertainment properties in the world.

                        Whether you are a player, collector, parent, professor, or fan, we strive to offer items featuring a wide range of Pokémon to allow you to express yourself.

                        We’re always open. Come say hello and take a look at the wide world of Pokémon!
                    </p>
                </div>
                <div class="bgimg-2">
                    <div class="caption">
                        <span class="border" style="background-color: rgb(0,0,0);
                              background-color: rgba(0,0,0,0.75);font-size:25px;color:white;">Connect with us!</span>
                    </div>
                </div>

                <div style="color:black;background-color:white;box-shadow:black 1px 10px 5px 2px;text-align:center;padding:50px 80px;text-align: justify;">
                    <h3 style="text-align:center;margin-bottom:30px;">Social Links</h3>
                    <div class="flex-container">
                        <a title = "Facebook" href="https://www.facebook.com/Pokemon.official.Singapore/"><i style="color:#4980c0;" class="fab fa-facebook fa-7x"></i></a>
                        <a title = "Instgram" href="https://www.instagram.com/pokemonofficial.sg/?hl=en"><i style="color:#4980c0;" class="fab fa-instagram fa-7x"></i></a>
                        <a title = "Twitter" href="https://twitter.com/Pokemon"><i style="color:#4980c0;" class="fab fa-twitter-square fa-7x"></i></a>
                    </div>
                </div>

                <div class="bgimg-3">
                    <div class="caption">
                        <span class="border" style="background-color: rgb(0,0,0);
                              background-color: rgba(0,0,0,0.75);font-size:25px;color:white;">Thank you for supporting us!</span>
                    </div>
                </div>

            </section>
            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>


</body>