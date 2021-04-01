<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
?>

<nav id="mainNav" class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" >
        <a id="logo" class="navbar-brand" href="index.php">
            <img src="/images/pokemart_logo.png" alt="Pokemart Logo" width="30" height="30">
            Pokemart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul id="mainList" class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="market.php">Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="promotions.php">Promotions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <?php
                // If user is logged in, show the upload page
                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="upload.php">Add Item</a>
                </li>
                <?php }?>
            </ul>
            <?php
            // Check if the user is already logged in, if yes then redirect him to welcome page
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                ?>
                <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class="nav-link" href="profile.php"><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
                </li>
                <li>
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
                <?php
            } else {
            ?>
            <ul id = "sideList" class="nav navbar-nav navbar-right">
                <li>
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li>
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
            <?php }?>
        </div>
    </div>
</nav>

<section id="space">
</section>
