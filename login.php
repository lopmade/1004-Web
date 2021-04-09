<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;
}

// Include config file
include "./login_process.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>

        <link rel="stylesheet" 
              href="css/login.css">

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
    </head>
    <body>
        <main>
            <div class="wrapper">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <span class="close">&times;</span>
                    <br>
                    <h1>Login</h1>
                    <p>Please fill in your credentials to login.</p>
                    <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
                        <input type="text" required placeholder="Username" name="username" class="form-control" value="<?php echo $username; ?>">
                    </div>    
                    <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
                        <input type="password" required placeholder="Password" name="password" class="form-control">
                        <span style="color:red" class="help-block"><br><?php echo $login_err; ?></span>

                    </div>
                    <div class="form-group">
                        <input type="submit" id="loginBtn" class="btn btn-primary" value="Login">
                    </div>
                    <p>Don't have an account? <a style="color:#004ad1" href="register.php">Sign up now</a>.</p>
                    <br>
                    <p>Back to <a style="color:#004ad1" href="index.php">Home</a>?</p>
                </form>
            </div>  
        </main>
    </body>
</html>