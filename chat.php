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

$chat_id = $_SESSION['chat_id']."log.html";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title>Tuts+ Chat Application</title>
        <meta name="description" content="Tuts+ Chat Application" />
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <h1>CHAT_ID:<?php echo $chat_id; ?></h1>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Chat<b></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
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
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
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
                        url: "./chats<?php echo $chat_id?>",
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
    </body>
</html>