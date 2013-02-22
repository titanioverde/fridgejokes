<?php

if ((isset($_COOKIE['fridgejokes_username'])) && (isset($_COOKIE['fridgejokes_password']))) {
	if (($_COOKIE['fridgejokes_username'] > "") && ($_COOKIE['fridgejokes_password']) > "") {
		if ($_SESSION['user_id'] == 0) {
			header("Location: login2.php");
		}
	}
}


?>

<script src='md5.js'></script>
<script type='text/javascript'>
function encrypt() {
	/* I keep on encrypting with standard MD5,
	in order to just not send plain text passwords. */
	pass_string = document.getElementById('login_password').value;
	document.getElementById('login_password').value = '';
	document.getElementById('login_password2').value = md5(pass_string);
	document.forms['login_form'].submit();
}
</script>

<?php

if (empty($joke_connection)) {
	echo $joke_connection;
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
//load_menu();

?>

<section id='login_window' class='login_window'>
	<header>Login</header>
	<form name='login_form' id='login_form' action='login2.php' method='post'>
		<fieldset>
			<input type='text' name='login_name' id='login_name' placeholder='User name' required />
		</fieldset>
		<fieldset>
			<input type='password' name='login_password' id='login_password' placeholder='Password' />
		</fieldset>
		<fieldset>
			<input type='submit' onClick='encrypt()'>
		</fieldset>
		<input type='hidden' name='login_password2' id='login_password2' />
	</form>
</section>