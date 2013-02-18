<?php
// Common functions library.
// If something is still written in Spanish... yes, it was copied from a previous project. We Spanish people are THAT lazy.
// Recycle, dude!

//---Files
$general_settings_file = "./general.conf";
$css_file = "./default.css";
$main_page = "./index.php";
$go_back_message = "Read another joke?";


//---E-mail


//---Debug
$social_link = false;


//Debug---

header('Content-Type: text/html; charset=utf-8');
/*echo "<link rel='stylesheet' href='$css_file'>";
echo "<script src='./prefixfree.min.js'></script>";
echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>";
*/
include "./lib/common_sql.php";


//---Login functions
//Your neighbourhood cookies, you know.
function GenerateCookie($username, $password, $lifetime) {
	setcookie("fridgejokes_username", $username, time() + $lifetime);
	setcookie("fridgejokes_password", $password, time() + $lifetime);
}

//Does this username actually exist?
function CheckUsername($username, $password) {
	$query = "SELECT * FROM users WHERE username='$username';";
	$settings = single_select_query($query);
	ApprovedUser($settings);
	if ($password == $settings['password']) {
		return $settings;
	}
	else {
		unset($settings);
		die("Wrong credentials.");
		//header("Location: login.php?error=1");
	}
}

//Server has to remember who this user is, through Session variables.
function SessionFill($settings, $username) {
	$_SESSION['current_username'] = $username;
	$_SESSION['user_id'] = $settings['user_ID'];
	$_SESSION['see_spoilers'] = $settings['see_spoilers'];
	$_SESSION['see_dislikes'] = $settings['see_dislikes'];
	$_SESSION['user_power'] = $settings['power'];
	$_SESSION['tune_player'] = $settings['tune_player'];
}

//Last time this user logged in. This is now.
function TimeRecord($username) {
	$query = "UPDATE users SET last_login=UNIX_TIMESTAMP() WHERE username='$username';";
	mysql_query($query);
	oh_my_error(mysql_error(), $query);
}

//Is this user able to log in now?
function ApprovedUser($settings) {
	if ($settings['status'] == "A") {
		return true;
	}
	if ($settings['status'] == "P") {
		die("We apologize, but your registration was not approved yet by Igor.");
	}
	if ($settings['status'] == "R") {
		die("Your user account was blocked or removed. If you may, call for Igor.");
	}
}

//Does this user have cookies? Take login information from them.
function ReadCookie() {
	if ((isset($_COOKIE['fridgejokes_username'])) && (isset($_COOKIE['fridgejokes_password']))) {
		if (($_COOKIE['fridgejokes_username'] > "") && ($_COOKIE['fridgejokes_password']) > "") {
			$username = $_COOKIE['fridgejokes_username'];
			$password = $_COOKIE['fridgejokes_password'];
			$settings = CheckUsername($username, $password);
			SessionFill($settings, $username);
			$lifetime = 604800;
			GenerateCookie($username, $password, $lifetime);
			TimeRecord($username);
			return true;
		//header("Location: view.php");
		}
		else return false;
	}
	else return false;
}

//If there's no started session nor cookie, create a new guest session with default settings.
function session_variables() {
    if (!isset($_SESSION['user_id'])) {
		if (!(ReadCookie())) {
			$_SESSION['user_id'] = 0;
            if (!isset($_SESSION['current_username'])) $_SESSION['current_username'] = "Anonymous MC";
			if (!isset($_SESSION['user_power'])) $_SESSION['user_power'] = -1;
			if (!isset($_SESSION['see_spoilers'])) $_SESSION['see_spoilers'] = false;
			if (!isset($_SESSION['see_dislikes'])) $_SESSION['see_dislikes'] = true;
			if (!isset($_SESSION['tune_player'])) $_SESSION['tune_player'] = 1;
		}
	}
}
//Login functions---

//Forbids entry for users without enough power level.
function check_power($minimum) {
	if (($_SESSION['user_power'] <= -1) || ($_SESSION['user_id'] == 0)) {
		die("Guest users aren't allowed here. Please, go look for a Velvet Key.");
	}
	if ($_SESSION['user_power'] < $minimum) {
		die("You\'re not allowed to enter here.");
		//ToDo: dictionary with a message for every kind of user.
	}
}

//Retrieve a single settings value.
function find_setting($setting) {
	$settings_query = "SELECT value FROM settings WHERE property = '$setting';";
	$result = single_select_query($settings_query);
	return $result['value'];
}

//Show a list of every available setting for this power level.
function settings_list($level) {
	$settings_query = "SELECT * FROM settings WHERE min_power = '$level';";
	$settings_result = mysql_query($settings_query);
	oh_my_error(mysql_error(), $settings_query);
	return $settings_result;
}

//Queries to sum and check votes for jokes.
function vote_count($joke, $mode, $user) {
    ///mode = likes, dislikes, loves
    if ($user) { //Did this user already vote this joke?
        $likes_query = "SELECT COUNT(*) FROM $mode WHERE joke_ID='$joke' AND user_ID='$user';";
    }
    else { //How many votes of this kind did this joke receive?
        $likes_query = "SELECT COUNT(*) FROM $mode WHERE joke_ID='$joke';";
    }
    $likes_result = mysql_query($likes_query);
    oh_my_error(mysql_error(), $likes_query);
    $likes_row = mysql_fetch_array($likes_result);
    mysql_free_result($likes_result);
    return $likes_row['COUNT(*)'];
}

/*function load_menu() {
	include "headermenu.php";
}*/

//Recurring user with no current session? Let's start the operation.
function auto_login() {
	if ($_SESSION['user_id'] == 0) {
		header("Location: login2.php");
	}
}

?>