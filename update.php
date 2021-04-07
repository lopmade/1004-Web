<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$item_name = $item_price = $description = $item_image = "";
$item_name_err = $item_price_err = $description_err = $imageUpload_err = "";

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Get URL parameter
    $id = trim($_GET["id"]);

    // Prepare a select statement
    $sql = "SELECT * FROM items_listing WHERE item_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = $id;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                  contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $item_name = $row["item_name"];
                $item_price = $row["item_price"];
                $description = $row["description"];
                $item_image = $row["item_image"];
            } else {
                // URL doesn't contain valid id. Redirect to landing page
                echo "Sorry, you've made an invalid request. Please";
                header("Refresh:2; url=./marketitem.php?item_id=$id");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close link if no form is submitted.
    if (!isset($_POST["id"]) && empty($_POST["id"])) {
        mysqli_close($link);
    };
} else {
    // URL doesn't contain id parameter. Redirect to landing page
    echo "Sorry, you've made an invalid request. Please";
    header("Refresh:2; url=marketitem.php?item_id=$id");
    exit();
}

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate item name
    $input_item_name = sanitize_input($_POST["item_name"]);
    if (empty($input_item_name)) {
        $item_name_err = "Please enter a item name.";
    } elseif (!filter_var($input_item_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $item_name_err = "Please enter a valid item name.";
    } else {
        $item_name = $input_item_name;
    }

    // Validate price
    $input_price = sanitize_input($_POST["item_price"]);
    if (empty($input_price)) {
        $item_price_err = "Please enter the price amount.";
    } elseif (!ctype_digit($input_price)) {
        $item_price_err = "Please enter a positive integer value.";
    } else {
        $item_price = $input_price;
    }

    // Validate description
    $input_description = sanitize_input($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Please enter the description.";
    } else {
        $description = $input_description;
    }

    $imgFile = sanitize_input($_FILES['fileToUpload']['name']);
    $tmp_dir = $_FILES['fileToUpload']['tmp_name'];
    $imgSize = sanitize_input($_FILES['fileToUpload']['size']);

    if ($imgFile) {
        $upload_dir = "./images/market/"; // upload directory
        $prefix = date('YmdHis') . '_' . str_pad(rand(1, 10000), 5, '0', STR_PAD_LEFT) . '_';
        $upload_file = $upload_dir . $prefix . basename($imgFile);
        $imgExt = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION)); // get image extension
        $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
        if (in_array($imgExt, $valid_extensions)) {
            if (!$imgSize != 0 && $imgSize < 1000000) {
                unlink($upload_dir . $item_image);
                if (!move_uploaded_file($tmp_dir, $upload_file)) {
                    $imageUpload_err = "Sorry, your file didn't upload successfully.";
                };
            } else {
                $imageUpload_err = "Sorry, your file is too large it should be less then 1M.";
            }
        } else {
            $imageUpload_err = "Sorry, only JPG, JPEG, PNG files are allowed.";
        }
    } else {
        // if no image selected the old image remain as it is.
        $prefix = "";
        $imgFile = $item_image; // old image from database
    }
    

    // Check input errors before inserting in database
    if (empty($item_name_err) && empty($item_price_err) && empty($description_err) && empty($imageUpload_err)) {
        // Prepare an update statement
        $sql = "UPDATE items_listing SET item_name=?, item_price=?, description=?,item_image=? WHERE item_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_itemname, $param_price, $param_descrption, $param_image, $param_id);

            // Set parameters
            $param_itemname = $item_name;
            $param_price = $item_price;
            $param_descrption = $description;
            $param_image = $prefix . basename($imgFile);
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Item updated successfully. Redirect to landing page
                echo "<h1>Item values successfully updated.</h1>";
                header("Refresh:2; url=./profile.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Update Record</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .wrapper{
                width: 600px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-5">Update Item</h2>
                        <p>Input value to edit and submit to update the Item.</p>
                        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?> " method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Item Name</label>
                                <input type="text" name="item_name" class="form-control <?php echo (!empty($item_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $item_name; ?>">
                                <span class="invalid-feedback"><?php echo $item_name_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <textarea name="item_price" class="form-control <?php echo (!empty($item_price_err)) ? 'is-invalid' : ''; ?>"><?php echo $item_price; ?></textarea>
                                <span class="invalid-feedback"><?php echo $item_price_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                <span class="invalid-feedback"><?php echo $description_err; ?></span>
                            </div>
                            <div class="form-group">
                                <p>Select image to upload:</p>
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                <span class="invalid-feedback"><?php echo $imageUpload_err; ?></span>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="profile.php" class="btn btn-secondary ml-2">Cancel</a>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>