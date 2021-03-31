<?php

if (count(get_included_files()) == 1) {
    header("Location: upload.php");
    exit();
}


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
    exit();
}
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$itemName = $desc = $itemPrice = "";
$itemUpload_err = $reCaptcha_err = $imageUpload_err = $itemName_err = $desc_err = $itemPrice_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uploadOk = 1;

    $secretKey = "6LdEzZYaAAAAAJPLUXtps0dYkl-kO2xMuMNiQQzp";
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response = file_get_contents($url);
    $response = json_decode($response);
    if ($response->success) {
        $uploadOk = 1;
    } else {
        $reCaptcha_err = "Please verify yourself before proceeding!";
        $uploadOk = 0;
    }



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

    if (!isset($_POST["desc"])) {
        $desc_err = "Please enter a description for the item!";
        $uploadOk = 0;
    } else {
        $desc = sanitize_input($_POST["desc"]);
        if (strlen($desc) < 8 || strlen($desc) > 200) {
            $desc_err = "Description must be between 8 and 200 characters long!";
            $uploadOk = 0;
        }
    }

    if (!isset($_POST["itemPrice"])) {
        $itemPrice_err = "Please enter a price for the item!";
        $uploadOk = 0;
    } else {
        $itemPrice = sanitize_input($_POST["itemPrice"]);
        $priceFormat = preg_match("/^([1-9][0-9]*|0)(\.[0-9]{2})?$/", $itemPrice);
        if (!$priceFormat) {
            $itemPrice_err = "Enter a valid price format without the dollar sign";
            $uploadOk = 0;
        }
    }


    // directory to upload image to
    $target_dir = "images/market/";
    // create prefix (basically a hash for each file)
    $prefix = date('YmdHis') . '_' . str_pad(rand(1, 10000), 5, '0', STR_PAD_LEFT) . '_';
    $tempfilename = sanitize_input($_FILES["fileToUpload"]["name"]);
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
                // echo "File is an image - " . $check["mime"] . ".";
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
                                // if pass the input requirements
                                if ($uploadOk == 1) {
                                    // if can upload the file
                                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                        // upload file sql query goes here
                                        // Prepare an insert statement

                                        $sql = "INSERT INTO items_listing (user_user_id,item_name,description,date_added,item_status,item_price,item_image1) VALUES (?,?,?,?,?,?,?)";
                                        $stmt = mysqli_prepare($link, $sql);
                                        // Bind variables to the prepared statement as parameters
                                        mysqli_stmt_bind_param($stmt, "sssssss", $param_user_user_id, $param_item_name, $param_description, $param_date_added, $param_item_status, $param_item_price, $param_item_image);
                                        $param_user_user_id = $_SESSION["user_id"];
                                        $param_item_name = $itemName;
                                        $param_description = $desc;
                                        $param_date_added = date("Y-m-d H:i:s");
                                        $param_item_status = '0';
                                        $param_item_price = $itemPrice;
                                        $param_item_image = $tempfilename;
                                        // Attempt to execute the prepared statement
                                        if (mysqli_stmt_execute($stmt)) {
                                            // Redirect to success page
                                            $_SESSION['message'] = "Successfully added $itemName to the market!";
                                            header("location: success.php");
                                        } else {
                                            $itemUpload_err = "Oops! Something went wrong. Please try again later.";
                                        }

                                        // Close statement
                                        mysqli_stmt_close($stmt);
                                    } else {
                                        $imageUpload_err = "Sorry, there was an error uploading your file.";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        $imageUpload_err = "No image detected. Please select an image.";
    }
    // Close connection
    mysqli_close($link);
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