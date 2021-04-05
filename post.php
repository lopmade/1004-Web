<?php

session_start();
$chat_id = $_SESSION['chat_id'];
$text = $_POST['text'];
date_default_timezone_set("Asia/Singapore");
$text_message = "<div class='msgln'><span class='chat-time'>" . date("g:i A") . "</span> <b class='user-name'>" . $_SESSION["username"] . "</b> " . stripslashes(htmlspecialchars($text)) . "<br></div>";
file_put_contents("./chats/$chat_id", $text_message, FILE_APPEND | LOCK_EX);
?>