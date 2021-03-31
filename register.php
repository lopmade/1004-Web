<?php
// Include config file
require_once "./register_process.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>

        <link rel="stylesheet" 
              href="css/register.css">

        <!-- bootstrap -->
        <link rel="stylesheet" 
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" >

        <!-- JavaScript Bundle with Popper -->
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js">
        </script>
		
        <!-- JavaScript for validation -->
        <script defer src="js/validator.js">
        </script>
		
        <!-- CSS for validation -->
        <link rel="stylesheet" 
              href="css/validator.css" >

        <!--jQuery-->
        <script defer src="js/jquery.min.js"></script>

        <!-- Custom JS -->
        <script defer src="js/main.js"></script>

        <!-- font awesome 5 -->
        <script defer src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script></head>

        <script>
            window.onload = function () {

                var span = document.getElementsByClassName("close")[0];

                span.onclick = function () {

                    if (confirm('Do you want to return to Home page?')) {
                        window.location.href = "index.php";
                    } else {
                        
                    }
                }
            };
        </script>
    <body>
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Sign Up<span class="close">&times;</span></h2>
                <p>Please fill this form to create an account.</p>
                <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                    <!--<label>First Name</label>-->
                    <input type="text" placeholder="First Name" required name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                    <span class="help-block"><?php echo $first_name_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                    <!--<label>Last Name</label>-->
                    <input type="text" placeholder="Last Name" required name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                    <span class="help-block"><?php echo $last_name_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <!--<label>E-Mail</label>-->
                    <input type="email" placeholder="Email" required name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>  
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <!--<label>Username</label>-->
                    <input type="text" placeholder="Username" required name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <!--<label>Password</label>-->
                    <input id="password" type="password" placeholder="Password" required name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><br><?php echo $password_err; ?></span>
                </div>
                <div id="message">
                    <h5>Password must contain the following:</h5>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">An <b>uppercase</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="specialchar" class="invalid">A <b>Special Character</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <!--<label>Confirm Password</label>-->
                    <input type="password" placeholder="Confirm Password" required name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" id="registerBtn"  class="btn btn-primary" value="Submit">
                    <input type="reset" id="resetBtn" class="btn btn-default" value="Reset">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>	
        </div>	
    </body>
</html>