<?php include './reset_password_process.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Pokemart - Reset Password</title>
        <?php
        include "header.inc.php";
        ?>

        <!-- JavaScript for validation -->
        <script defer src="js/validator.js">
        </script>

        <!-- CSS for validation -->
        <link rel="stylesheet" 
              href="css/validator.css" >
        
        <link rel="stylesheet" 
              href="css/resetpassword.css" >

    </head>
    <body>

        <main class = "main">

            <?php
            include "nav.inc.php";
            ?>

            <section id="mainContent" class = "section">
                <h2>Reset Password</h2>
                <p>Please fill out this form to reset your password.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="form-group">
                        <input id="password" type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                        <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input id="resetPasswordBtn" type="submit" class="btn btn-primary" value="Submit">
                        <a href="profile.php"><input id="cancelBtn" type="button" class="btn btn-primary" value="Cancel"></a>
                    </div>
                    <div id="message">
                        <h5>Password must contain the following:</h5>
                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                        <p id="capital" class="invalid">An <b>uppercase</b> letter</p>
                        <p id="number" class="invalid">A <b>number</b></p>
                        <p id="specialchar" class="invalid">A <b>Special Character</b></p>
                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                    </div>
                </form>
            </section>

            <?php
            include "footer.inc.php";
            ?>
        </main>
    </body>
</html>