<?php
    require_once "config.php";

    if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            if($check !== false) {
                
                $sql = "UPDATE user SET profile_picture = ? WHERE user_id = ?";
        
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    $data = base64_encode(file_get_contents( $_FILES["fileToUpload"]["tmp_name"] ));
                    $img_url_path = "data:".$check["mime"].";base64,";
                    $img = $image_url_path.$data;
                    echo $img;
                    mysqli_stmt_bind_param($stmt, "si", $param_profile, $param_id);

                    // Set parameters
                    $param_profile = $data;
                    $param_id = $_SESSION["user_id"];

                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        echo "Picture uploaded successfully. Redirecting you back to profile page";
                        header("Refresh:3; url=profile.php");
                        exit();
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                
            } else {
                    echo "File is not an image.";
            }
        }
    }
?> 

