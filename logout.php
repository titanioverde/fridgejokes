<?php
session_start();
include "common.php";
//guest_forbidden();
setcookie("fridgejokes_username", "", time() - 3600);
setcookie("fridgejokes_password", "", time() - 3600);
session_destroy();

echo "<link rel='stylesheet' href='$css_file'>";


echo "<p>&gt; You decided to go to bed early.</p>";
echo "<p><a href='$main_page'>$go_back_message</a></p>";




?>