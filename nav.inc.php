<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;

    include "login_process.php";
    include "register_process.php";
}
?>

<script>
    window.onload = function () {
        var modal1 = document.getElementById("myModal1");
        var modal2 = document.getElementById("myModal2");

        var loginLink = document.getElementById("loginLink");
        var registerLink = document.getElementById("registerLink");

        var loginBtn = document.getElementById("loginBtn");
        var registerBtn = document.getElementById("registerBtn");

        var close1 = document.getElementsByClassName("login-close")[0];

        var close2 = document.getElementsByClassName("register-close")[0];

        loginLink.onclick = function () {
            modal2.style.display = "none";
            modal1.style.display = "block";
        }

        registerLink.onclick = function () {
            modal1.style.display = "none";
            modal2.style.display = "block";
        }

        loginBtn.onclick = function () {
            modal1.style.display = "block";
        }

        registerBtn.onclick = function () {
            modal2.style.display = "block";
        }

        close1.onclick = function () {
            modal1.style.display = "none";
        }

        close2.onclick = function () {
            modal2.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }

    }


</script>

<link rel="stylesheet" 
      href="css/modal.css">

<nav id="mainNav" class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" >
        <a id="logo" class="navbar-brand" href="index.php">
            <img src="/images/pokemart_logo.png" class="d-inline-block align-top" alt="Pokemart Logo" width="30" height="30">
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
            </ul>
            <?php
            // Check if the user is already logged in, if yes then redirect him to welcome page
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                ?>
                <ul id="sideList" class="nav navbar-nav navbar-right">
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
                <ul id="sideList" class="nav navbar-nav navbar-right">
                    <li>
                        <a class="nav-link" id="registerBtn">Register</a>
                    </li>
                    <li>
                        <a class="nav-link" id="loginBtn">Login</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Login Modal -->
<div id="myModal1" class="modal1">
    <div class="login-modal-content">
        <div class="login-modal-header">
            <h2>Login</h2>
            <span class="login-close">&times;</span>
        </div>
        <div class="login-modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
                <h6>Please fill in your credentials to login.</h6>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" required placeholder="Username" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" required placeholder="Password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" id="loginBtn" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <span style="color:blue; text-decoration: underline;" id="registerLink">Sign up now</span>.</p>
            </form>
        </div>
        <div class="login-modal-footer">
        </div>
    </div>
</div>



<!-- Register Modal-->
<div id="myModal2" class="modal2">
    <div class="register-modal-content">
        <div class="register-modal-header">
            <h2>Register</h2>
            <span class="register-close">&times;</span>
        </div>
        <div class="register-modal-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h6>Please fill this form to create an account.</h6>
                <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                    <label>First Name</label>
                    <input type="text" placeholder="First Name" required name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                    <span class="help-block"><?php echo $first_name_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                    <label>Last Name</label>
                    <input type="text" placeholder="Last Name" required name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                    <span class="help-block"><?php echo $last_name_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>E-Mail</label>
                    <input type="email" placeholder="Email" required name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>  
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Username</label>
                    <input type="text" placeholder="Username" required name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div id="message">
                    <p>Password must contain the following:</p>
                    <ul>
                        <li id="letter" class="invalid">A <b>lowercase</b> letter</li>
                        <li id="capital" class="invalid">A <b>capital (uppercase)</b> letter</li>
                        <li id="number" class="invalid">A <b>number</b></li>
                        <li id="specialchar" class="invalid">A <b>Special Characters</b></li>
                        <li id="length" class="invalid">Minimum <b>8 characters</b></li>
                    </ul>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input id="password" type="password" placeholder="Password" required name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" required name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" id="registerBtn"  class="btn btn-primary" value="Submit">
                    <br>
                    <input type="reset" id="resetBtn" class="btn btn-default" value="Reset">
                </div>
                <p>Already have an account? <span style="color:blue; text-decoration: underline;" id="loginLink" >Login here</span>.</p>
            </form>
        </div>
        <div class="register-modal-footer">
        </div>
    </div>
</div>

