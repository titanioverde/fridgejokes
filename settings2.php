<?php

include "common.php";
session_start();
$joke_connection = connect_sql();
session_variables();
//load_menu();
check_power(0);

echo "<link rel='stylesheet' href='$css_file'>";


$settings_level = $_POST['settings_level'];

function look_and_save_general_settings($level) {
	$settings_result = settings_list(1);
	
	while ($settings_row = mysql_fetch_array($settings_result)) {
		$setting_name = $settings_row['property'];
		$setting_value = mysql_real_escape_string($_POST[$setting_name]);
		if ($setting_value < 0) $setting_value = 0;
		$query = "UPDATE settings SET value = '$setting_value' WHERE property = '$setting_name';";
		mysql_query($query);
		oh_my_error(mysql_error(), $query);
	}	
}

//Normal user.
if ($settings_level == 0) {
	$user_id = $_SESSION['user_id'];
	$user_email = mysql_real_escape_string($_POST['email_address']);
	$user_location = mysql_real_escape_string($_POST['location']);
	$user_bio = mysql_real_escape_string($_POST['bio']);
	$user_website = mysql_real_escape_string($_POST['website']);
	
	if ($_POST['see_spoilers'] == 'on') {
		$see_spoilers = 1;
	}
	else {
		$see_spoilers = 0;
	}
	
	if ($_POST['see_dislikes'] == 'on') {
		$see_dislikes = 1;
	}
	else {
		$see_dislikes = 0;
	}
	
	if ($_POST['tune_player'] == 'disable') {
		$tune_player = -1;
	}
	elseif ($_POST['tune_player'] == 'paused') {
		$tune_player = 0;
	}
	else {
		$tune_player = 1;
	}
	
	$settings_query = "UPDATE users SET email = '$user_email', see_spoilers = '$see_spoilers', see_dislikes = '$see_dislikes', tune_player = '$tune_player',
		location = '$user_location', bio = '$user_bio', website = '$user_website' WHERE user_ID = '$user_id';";
	mysql_query($settings_query);
	oh_my_error(mysql_error(), $settings_query);
	
	$success = true;
}

//Moderator.
if ($settings_level == 1) {
	check_power(1);
	look_and_save_general_settings(1);
	$success = true;
}

//Administrator.
if ($settings_level == 2) {
	check_power(2);
	look_and_save_general_settings(2);
	$success = true;
}

echo "<div class='shadow'>";

if ((!$success) || empty($success)) {
	echo "<p class='error'>Life is truth and never a dream...<br />Press your browser's Back button, and try again.</p>";
}
else {
	if ($settings_level == 0) {
		$_SESSION['see_spoilers'] = $see_spoilers;
		$_SESSION['see_dislikes'] = $see_dislikes;
		$_SESSION['tune_player'] = $tune_player;
	}

	echo "<p>Settings succesfully saved.</p>";
	echo "<p><a href='$main_page'>$go_back_message</a></p>";
	
}
echo "</div>";

?>