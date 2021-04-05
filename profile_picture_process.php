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
    // directory to upload image to
    $target_dir = "images/profile/";
    // create prefix (basically a hash + username for each file)
    $prefix = date('YmdHis') . '_' . str_pad(rand(1, 10000), 5, '0', STR_PAD_LEFT) . '_' . $_SESSION['username']. '_';
    $tempfilename = $_FILES["fileToUpload"]["name"];
    $target_file = $target_dir . $prefix . basename($tempfilename);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is input or not
    if (isset($_POST["submit"])) {
        if ($_FILES["fileToUpload"]["size"] == 0) {
            $imageUpload_err = "No image detected. Please select an image.";
        } else {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            // Check if file is an actual image
            if ($check == false) {
                $imageUpload_err = "File is not an image.";
            } else {
                // Check if file already exists in the very very rare chance that two files is uploaded at the same time with the same name and hash
                if (file_exists($target_file)) {
                    $imageUpload_err = "Sorry, file already exists.";
                } else {
                    // Check file name ensure <= 200 characters
                    if (strlen($tempfilename) > 200) {
                        $imageUpload_err = "Max file name is 200 characters.";
                    } else {
                        // Check file size
                        if ($_FILES["fileToUpload"]["size"] > 1000000) {
                            $imageUpload_err = "Sorry, your file is too large. Max is 1MB";
                        } else {
                            // Allow certain file formats
                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                $imageUpload_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                            } else {
                                // if can upload the file
                                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                    // upload file sql query goes here
                                    // Prepare an insert statement

                                    $sql = "UPDATE user SET profile_picture = ? WHERE user_id = ?";
                                    $stmt = mysqli_prepare($link, $sql);
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "ss", $param_profile_picture, $param_user_id);
                                    $param_profile_picture = $prefix . basename($tempfilename);
                                    $param_user_id = $_SESSION["user_id"];
                                    
                                    // Attempt to execute the prepared statement
                                    if (mysqli_stmt_execute($stmt)) {
                                        // Redirect to success page
                                        echo "Picture uploaded successfully. Redirecting you back to profile page.";
                                        header("Refresh:3; url=profile.php");
                                    } else {
                                        $imageUpload_err = "Oops! Something went wrong. Please try again later.";
                                    }
                                    echo "Picture uploaded successfully. Redirecting you back to profile page.";
                                    header("Refresh:3; url=profile.php");
                                    // Close statement
                                    mysqli_stmt_close($stmt);
                                } else {
                                    $imageUpload_err = "Sorry, there was an error uploading your file. Redirecting you back to profile page.";
                                    header("Refresh:3; url=profile.php");
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "No image detected. Please select an image.";
        header("Refresh:3; url=profile.php");
    }
    // Close connection
    mysqli_close($link);
}
?> 

