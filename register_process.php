<?php
// Include config file
require_once "config.php";

require './email_credentials.php';
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $first_name = $last_name = $email = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
     // Validate first_name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter a first name.";
    } else{
            $first_name = trim($_POST["first_name"]);
    }
	
	// Validate last_name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter a last name.";
    } else{                     
            $last_name = trim($_POST["last_name"]);
    }
 
     // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_id FROM user WHERE email = ?";
        
        $stmt = mysqli_prepare($link, $sql);
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = trim($_POST["email"]);


        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                $email_err = "This email is already taken.";
            } else{
                $email = trim($_POST["email"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT user_id FROM user WHERE username = ?";
        
        $stmt = mysqli_prepare($link, $sql);
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = trim($_POST["username"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password strength
    $password = $_POST["password"];
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen(trim($password)) < 7){
        $password_err = "Password must have mininally 8 characters, a lower case, an upper case, a number and a special character.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err && empty($email_err) && empty($first_name_err) && empty($last_name_err))){
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (username, password, email, first_name, last_name, token) VALUES (?, ?, ?, ?, ? ,?)";
         
        $stmt = mysqli_prepare($link, $sql);
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $param_first_name, $param_last_name, $param_token);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash
            $param_email = $email;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_token = md5($param_username . date("dmYhis"));
            $url = $_SERVER['SERVER_NAME'];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
                
                $mail = new PHPMailer(true);
                try {
                    $mail->Username   = EMAIL;                          //SMTP username
                    $mail->Password   = PASS;                           //SMTP password
                    $mail->setFrom(EMAIL , 'POKESTOP');                 //SEND FROM
                    $mail->addAddress($param_email, $param_first_name); //SEND TO
                    
                    //EMAIL CONTENT
                    $mail->isHTML(true);
                    $mail->Subject = 'Regisration verification!';
                    $mail->Body = '' ;
                    $mail->AltBody = '';

                echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>