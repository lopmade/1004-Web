<?php
    require_once "config.php";

    // Initialize the session
    if (!isset($_SESSION)) {
        session_start();
    }

    // Check if the user is logged in, if not then redirect him to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    
    if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            if($check !== false) {
                
                $data = file_get_contents( $_FILES["fileToUpload"]["tmp_name"]);
                $img = base64_encode($data);
                
                $sql = "UPDATE user SET profile_picture = ? WHERE user_id = ?";
                
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "si", $param_profile, $param_id);

                    // Set parameters
                    $param_profile = $data;
                    $param_id = $_SESSION["user_id"];

                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        echo "Picture uploaded successfully. Redirecting you back to profile page.";
                        header("Refresh:3; url=profile.php");
                    } else{
                        echo mysqli_report(MYSQLI_REPORT_ERROR);
                    }
                    
                // Close statement
                mysqli_stmt_close($stmt);
                
            } else {
                    echo "File is not an image.";
            }
        }
        // Close connection
        mysqli_close($link);
    }
?> 

