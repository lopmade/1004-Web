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
    exit;
}
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$itemName = $desc = "";
$imageUpload_err = $itemName_err = $desc_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uploadOk = 1;
    if (!isset($_POST["itemName"])) {
        $itemName_err = "Please enter a name for the item!";
        $uploadOk = 0;
    } else {
        $itemName = sanitize_input($_POST["itemName"]);
        if (strlen($itemName) < 8 || strlen($itemName) > 45) {
            $itemName_err = "Item Name must be between 8 and 45 characters long!";
            $uploadOk = 0;
        }
    }

    if (!isset($_POST["$desc"])) {
        $desc_err = "Please enter a description for the item!";
        $uploadOk = 0;
    } else {
        $desc = sanitize_input($_POST["desc"]);
        if (strlen($desc) > 200) {
            $desc_err = "Description must be less than 200 characters long!";
            $uploadOk = 0;
        }
    }


    // directory to upload image to
    $target_dir = "images/market/";
    // create prefix (basically a hash for each file)
    $prefix = date('YmdHis') . '_' . str_pad(rand(1, 10000), 5, '0', STR_PAD_LEFT) . '_';
    $target_file = $target_dir . $prefix . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["fileToUpload"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        // if file is an actual image
        if ($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";

            // Check if file already exists in the very very rare chance that two files is uploaded at the same time with the same name and hash
            if (file_exists($target_file)) {
                $imageUpload_err = "Sorry, file already exists.";
                
            } else {
                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 500000) {
                    $imageUpload_err = "Sorry, your file is too large.";
                    
                } else {
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $imageUpload_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                        
                    } else {
                        // if can upload the file
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            // upload file sql query goes here
                            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                        } else {
                            $imageFileType = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
            }
        } else {
            $imageUpload_err = "File is not an image.";
            $uploadOk = 0;
        }
    } else {
        $imageUpload_err = "No image detected. Please select an image.";
    }
}

function sanitize_input($data) {
    // remove extra characters like whitespaces,tabs
    $data = trim($data);
    // remove slashes
    $data = stripslashes($data);
    // remove < and >
    $data = htmlspecialchars($data);
    return $data;
}

?>