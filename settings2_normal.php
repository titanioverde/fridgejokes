<?php

include "common.php";
session_start();
$joke_connection = connect_sql();
session_variables();
load_menu();
check_power(0);

$user_id = $_SESSION['user_id'];
$user_email = $_POST['email_address'];
$user_location = $_POST['location'];
$user_bio = $_POST['bio'];
$user_website = $_POST['website'];
if ($_POST['see_spoilers'] == 'checked') {
	$user_spoilers = 1;
}
else {
	$user_spoilers = 0;
}

$settings_query = "UPDATE users SET email = '$user_email', see_spoilers = '$user_spoilers',
	location = '$user_location', bio = '$user_bio', website = '$user_website' WHERE user_ID = '$user_id';";
mysql_query($settings_query);
oh_my_error(mysql_error(), $settings_query);

echo "Settings succesfully saved.";

?>