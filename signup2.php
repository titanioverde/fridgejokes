<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}

echo "<div class='shadow'>";

if ($_SESSION['user_power'] > -1) {
    die("<p class='error'>You've already logged in. If you really want to discard your current Persona, log out first.</p></div>");
}

//Let's hope for 2 modes: automatic, and with admin/moderator permission.

$username = $_POST['username'];
$password = $_POST['password1'];
$email = $_POST['email1'];

$user_query = "INSERT INTO users (username, password, email, status, first_signup, power) VALUES ('$username', '$password', '$email', 'P', UNIX_TIMESTAMP(), '0');";

mysql_query($user_query);
oh_my_error(mysql_error(), $query);

echo "<p>User registered. Please, wait for Igor to send you the key.</p>";
echo "<p><a href='$main_page'>$go_back_message</a></p>";
echo "</div>";
?>