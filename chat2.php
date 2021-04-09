<?php
// Initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require_once 'config.php';
$chat_id = $_POST['chat_id'];
$seller_id = $_POST['seller_id'];
$item_id = $_POST['item_id'];
$_SESSION['chat_id'] = $chat_id;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        include "header.inc.php";
        ?>
        <link rel="stylesheet" href="css/main.css" />
        <link rel="stylesheet" href="css/chat.css" />
        <title>Chat</title>

    </head>
    <body>
        <main class="main">
            <?php
            include "nav.inc.php";
            ?>

            <div id="wrapper">
                <div id="menu">
                    <p style="font-size: 24px;"class="welcome">Chat<b></b></p>
                    <p class="logout"><a style="text-decoration:none;" id="exit" href="#">Exit Chat</a></p>
                </div>

                <div id="chatbox">
                    <?php
                    if (file_exists("./chats/$chat_id") && filesize("./chats/$chat_id") > 0) {
                        $contents = file_get_contents("./chats/$chat_id");
                        echo $contents;
                    }
                    ?>
                </div>

                <form name="message" action="">
                    <input aria-label="messagebox" name="usermsg" type="text" id="usermsg" />
                    <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
                </form>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                // jQuery Document
                $(document).ready(function () {});
            </script>
            <script type="text/javascript">
                // jQuery Document
                $(document).ready(function () {
                    $("#submitmsg").click(function () {
                        var clientmsg = $("#usermsg").val();
                        $.post("post.php", {text: clientmsg});
                        $("#usermsg").val("");
                        return false;
                    });

                    function loadLog() {
                        var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request

                        $.ajax({
                            url: "./chats/<?php echo $chat_id ?>",
                            cache: false,
                            success: function (html) {
                                $("#chatbox").html(html); //Insert chat log into the #chatbox div

                                //Auto-scroll           
                                var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                                if (newscrollHeight > oldscrollHeight) {
                                    $("#chatbox").animate({scrollTop: newscrollHeight}, 'normal'); //Autoscroll to bottom of div
                                }
                            }
                        });
                    }

                    setInterval(loadLog, 2500);

                    $("#exit").click(function () {
                        var exit = confirm("Return to Item?");
                        if (exit == true) {
                            window.history.back();
                        }
                    });
                });
            </script>
            <?php
            include "footer.inc.php";
            ?>
        </main>

    </body>
</html>