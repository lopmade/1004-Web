<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}
// Include config file
include("config.php");

// Process delete operation after confirmation
if (isset($_POST["id"]) && !empty($_POST["id"])) {

    // Set SQL delete query
    $del_item = "delete il, io"
            . " from items_listing il inner join item_offer io"
            . " on il.item_id = io.offer_item_id"
            . " where il.item_id=?";
echo $del_item;
    // Prepare a delete statement
    if ($run_item = mysqli_prepare($link, $del_item)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($run_item, "i", $param_id);

        // Set parameters
        $param_id = sanitize_input($_POST["id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($run_item)) {
            // Records deleted successfully. Redirect to landing page
            echo "<h1>Item successfully deleted.</h1>";
            header("Refresh:2; url=./market.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["id"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        echo "Sorry, you've made an invalid request. Please";
        header("Refresh:2; url=./marketitem.php?item_id=$id");
        exit();
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete Record</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .wrapper{
                width: 600px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <main class="main">
            <section class="wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mt-5 mb-3">Delete Item</h2>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="alert alert-danger">
                                    <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                                    <p>Are you sure you want to delete this Item?</p>
                                    <p>
                                        <input type="submit" value="Yes" class="btn btn-danger">
                                        <a href="marketitem.php?item_id=<?php echo $_GET["id"]?>" class="btn btn-secondary">No</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>        
                </div>
            </section>
        </main>
    </body>
</html>