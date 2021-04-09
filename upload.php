<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
           window.alert('Please login first!')
           window.location.href='login.php';
       </SCRIPT>
       <NOSCRIPT>
           <a href='login.php'>Please login first. Click here if you are not redirected.</a>
       </NOSCRIPT>");
    //header("location: login.php");
    exit;
}

require_once "./upload_process.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Pokemart - Add Item</title>
        <?php
        include "header.inc.php";
        ?>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <link rel="stylesheet" href="css/upload.css" />
    </head>
    <body>
        <main class = "main">
            <?php
            include "nav.inc.php";
            ?>
            <section id="mainContent" class = "section">
                <h1 style="color:#747474">Creating Listing</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group <?php echo (!empty($imageUpload_err)) ? 'has-error' : ''; ?>">

                        <?php
                        if (!empty($itemUpload_err)) {
                            echo "<h2>$itemUpload_err</h2>";
                        }
                        ?>
                        <label>Select image to upload:<br><br>
                        <input type="file" name="fileToUpload" id="fileToUpload" title="File to upload"></label<br>

                        <?php
                        // to debug use $imageUpload_err = "abc";
                        if (!empty($imageUpload_err)) {
                            echo '<span class="help-block">' . $imageUpload_err . '</span>';
                        }
                        ?>

                    </div>
                    <div class="form-group <?php echo (!empty($itemName_err)) ? 'has-error' : ''; ?>">
                        <input type="text" required placeholder="Item Name" minlength="8" maxlength="45" name="itemName" value="<?php echo $itemName; ?>"><br>
                        <?php
                        if (!empty($itemName_err)) {
                            echo '<span class="help-block">' . $itemName_err . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group <?php echo (!empty($desc_err)) ? 'has-error' : ''; ?>">
                        <input type="text" required placeholder="Description" minlength="8" maxlength="200" name="desc" value="<?php echo $desc; ?>"><br>
                        <?php
                        if (!empty($desc_err)) {
                            echo '<span class="help-block">' . $desc_err . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group <?php echo (!empty($itemPrice_err)) ? 'has-error' : ''; ?>">
                        <input type="text" required placeholder="Item Price" maxlength="200" name="itemPrice" value="<?php echo $itemPrice; ?>"><br>
                        <?php
                        if (!empty($itemPrice_err)) {
                            echo '<span class="help-block">' . $itemPrice_err . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6LdEzZYaAAAAACo5nBd5kfX5SPb3sydsxm6O3hXg"></div><br>
                        <?php
                        // $reCaptcha_err = "sasas";
                        if (!empty($reCaptcha_err)) {
                            echo '<span class="help-block">' . $reCaptcha_err . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="uploadItemBtn" class="btn btn-primary" value="Upload Item" name="submit">
                    </div>
                </form>
            </section>
        </main>
    </body>
</html>
