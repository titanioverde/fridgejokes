<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
check_power(2);

if (empty($_POST['user'])) {
	die("No user specified.");
}

$new_user = mysql_real_escape_string($_POST['user']);
$new_name = mysql_real_escape_string($_POST['name']);
$new_email = mysql_real_escape_string($_POST['email']);

$user_query = "UPDATE users SET status = 'A' WHERE user_ID = '".$new_user."';";
mysql_query($user_query);
oh_my_error(mysql_error(), $user_query);
echo "<p>User $new_user approved.</p>";

//Send e-mail.
$message = "[phone call]\n
Hello, $new_name. This is Margaret.\n
Your signup information has been approved. You can log in whenever you want.\n
I suggest you to enter your Settings (in the Menu) and write down your Profile.\n\n

Also, would you be so kind to bring me a Pyro Jack with Megidolaon? Much appreciated.\n\n

Fridge Jokes\n
http://titanioverde.koding.com/fridgejokes";
if (mail($new_email, "Registration at Fridge Jokes confirmed", $message)) {
	echo "<p>E-mail notification sent.</p>";
}
else {
	echo "<p>Error sending the e-mail notification.</p>";
}

?>