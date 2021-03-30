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
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
    </head>
    <body>
        <div class="wrapper">
            <?php
            include "nav.inc.php";
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <p>Select image to upload:</p>
                <div class="form-group <?php echo (!empty($imageUpload_err)) ? 'has-error' : ''; ?>">
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <span class="help-block"><?php echo $imageUpload_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($itemName_err)) ? 'has-error' : ''; ?>">
                    <input type="text" required placeholder="Item Name" maxlength="45" name="itemName" value="<?php echo $itemName; ?>">
                    <span class="help-block"><?php echo $itemName_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($desc_err)) ? 'has-error' : ''; ?>">
                    <input type="text" required placeholder="Description" maxlength="200" name="desc" value="<?php echo $desc; ?>">
                    <span class="help-block"><?php echo $desc_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" id="uploadItemBtn" class="btn btn-primary" value="Upload Item">
                </div>
            </form>
        </div>
    </body>
</html>

<?php ?>