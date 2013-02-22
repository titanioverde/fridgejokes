

<?php
error_reporting(E_ALL);
session_start();
include "common.php";
$login_connection = connect_sql();

echo "<link rel='stylesheet' href='$css_file'>";

//The actual job of opening a user session.
//First from Cookies, if they exist.

echo "<div class='shadow'>";

if (ReadCookie()) {
	echo "All done.";
	echo $_SESSION['user_id'];
}
//Else from POST variables, coming from login.php.
elseif (isset($_POST['login_name']) && isset($_POST['login_password2'])) {
	$username = $_POST['login_name'];
	$password = $_POST['login_password2'];
	$lifetime = 604800;
	$settings = CheckUsername($username, $password);
	SessionFill($settings, $username);
	GenerateCookie($username, $password, $lifetime);
	TimeRecord($username);
	//header("Location: view.php");
}

elseif (isset($_POST['login_username']) && isset($_COOKIE['fridgejokes_username'])) { // Nothing? Guest you are. ToDo
	die("You're already logged in. Go back to Dojima's house.</div>");
}
echo "<p>Welcome back, ".$_SESSION['current_username'].".<br />";
echo "<p><a href='".$main_page."'>Enter, please.</a></p>";
echo "</div>";
//load_menu();

?>

