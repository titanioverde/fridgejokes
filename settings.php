<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
check_power(0);

//Normal user settings:
$user_id = $_SESSION['user_id'];

if ($_SESSION['user_power'] >= 2) {
	echo "<script src='lib/send.js'></script>";
}

$user_settings_query = "SELECT email, see_spoilers, location, bio, website FROM users WHERE user_ID = '$user_id';";
$user_settings_row = single_select_query($user_settings_query);

$user_email = $user_settings_row['email'];
$user_location = $user_settings_row['location'];
$user_bio = $user_settings_row['bio'];
$user_website = $user_settings_row['website'];

//ToDo: AJAX to save settings inmediately.

if ($_SESSION['user_power'] >= 1) {
    echo "<nav class='subsettings'>";
    echo "<a class='subsettings' onClick='settings_pass(0)'>Normal user</a>";
    echo "<a class='subsettings' onClick='settings_pass(1)'>Moderator</a>";
    if ($_SESSION['user_power'] >= 2) {
        echo "<a class='subsettings' onClick='settings_pass(2)'>Administrator</a>";
    }
    echo "</nav>";
}

echo "<section id='settings0' class='country normal_user settings'>";
echo "<header>Contents</header>";
echo "<form method='post' action='settings2.php'>";

//See spoilers.
echo "<fieldset>
    <label><input type='checkbox' name='see_spoilers' id='see_spoilers_setting'";
if ($_SESSION['see_spoilers']) {
    echo " checked='checked' value='on'";
}
echo " />";
echo "Read spoiler jokes<br /><span class='miniwarning'>You have played / read enough about Persona 3 and 4 first editions to understand jokes about Evokers, Adachi, Nanako&#39;s science project, etc.</span></label>
</fieldset>";

//See dislikes.
echo "<fieldset>
    <label><input type='checkbox' name='see_dislikes' id='see_dislikes_setting'";
if ($_SESSION['see_dislikes']) {
    echo " checked='checked' value='on'";
}
echo " />";
echo "Read again disliking jokes<br /><span class='miniwarning'>You don&#39;t mind seeing again jokes you already marked with your \"Dislike\" vote.</span></label>
</fieldset>";

//Tune player.
$tune_player = $_SESSION['tune_player'];
echo "<fieldset>
	Tune player behavior<br />";
	echo "<label><input type='radio' name='tune_player' value='autoplay'"; if ($tune_player == 1) echo " checked='checked'"; echo " /> Start playing immediately</label><br />";
	echo "<label><input type='radio' name='tune_player' value='paused'"; if ($tune_player == 0) echo " checked='checked'"; echo " /> Play manually <span class='miniwarning'>(You shall click on the player bar to control it)</span></label><br />";
	echo "<label><input type='radio' name='tune_player' value='disable'"; if ($tune_player == -1) echo " checked='checked'"; echo " /> Never load it</label><br />";
echo "</fieldset>";

//Profile options.
echo "<header>Profile</header>";
echo "<fieldset>
    <label><span class='setting_title'>Your e-mail address: </span><input type='email' name='email_address' id='email_address_setting' value=\"".htmlspecialchars($user_email)."\" />
	<br /><span class='miniwarning'>You will receive information here in case of issues.</snap></label>
</fieldset>

<fieldset>
    <label><span class='setting_title'>Your location: </span><input type='text' name='location' id='location_setting' value=\"".htmlspecialchars($user_location)."\" />
	<br /><span class='miniwarning'>The place where you live when you're not in Inaba.</span></label>
</fieldset>

<fieldset>
    <label><span class='setting_title'>Bio: </span><input type='text' name='bio' id='bio_setting' value=\"".htmlspecialchars($user_bio)."\" />
	<br /><span class='miniwarning'>Some public information about you.</span></label>
</fieldset>

<fieldset>
    <label><span class='setting_title'>Website: </span><input type='url' name='website' id='website_setting' value=\"".htmlspecialchars($user_website)."\" />
	<br /><span class='miniwarning'>An URL to let people see other faces of you.</span></label>
</fieldset>

<input type='hidden' name='settings_level' value='0' />
<input type='submit' />";

echo "</form></section>";

//Moderator settings.

if ($_SESSION['user_power'] >= 1) {
    echo "<section id='settings1' class='country moderator settings'>";
    echo "<header>Moderation settings</header>";
    echo "<form method='post' action='settings2.php'>";
    
    echo "<p>No settings yet.</p>";
    
    echo "<input type='hidden' name='settings_level' value='1' />";
    //echo "<input type='submit' />";
    echo "</form></section>";
}

//Admin settings.

if ($_SESSION['user_power'] >= 2) {
    
    //Data query:
    $settings_query = "SELECT * FROM settings WHERE min_power = '2';";
    $settings_result = mysql_query($settings_query);
    oh_my_error(mysql_error(), $settings_query);
    $max_settings = mysql_num_rows($settings_result);
    
    //Form:
    echo "<section id='settings2' class='country administrator settings'>";
    echo "<header>Administrator settings</header>";
    echo "<form method='post' action='settings2.php'>";
        
    $current_setting = 0;
    while ($current_setting < $max_settings) {
        $setting_row = mysql_fetch_array($settings_result);
        $setting_name = $setting_row['property'];
        $setting_description = $setting_row['description'];
        $setting_value = $setting_row['value'];
        $setting_type = $setting_row['form_type'];
        
        echo "<fieldset>";
        echo "<label for='$setting_name'>$setting_description";
        echo "<input type='$setting_type' name='$setting_name' id='$setting_name' value='$setting_value' /></label>";
        echo "</fieldset>";
        $current_setting += 1;
    }
    
    echo "<input type='hidden' name='settings_level' value='2' />";
    echo "<input type='submit' />";
    echo "</form>";
    mysql_free_result($settings_result);
	unset($current_setting);
	//End of form.
	
	//Pending users.
	echo "<header>Users management</header><div class='pending_user'";
	
	$users_query = "SELECT user_ID, username, email, first_signup FROM users WHERE status='P' ORDER BY first_signup ASC;";
	$users_result = mysql_query($users_query);
	oh_my_error(mysql_error(), $users_query);
	$max_users = mysql_num_rows($users_result);
	
	$current_user = 0;
	while ($current_user < $max_users) {
		$user_row = mysql_fetch_array($users_result);
		$user_result_id = $user_row['user_ID'];
		
		echo "<p class='moderation' id='user$user_result_id'>";
		echo "ID: $user_result_id. Name: ".$user_row['username'].". E-mail: ".$user_row['email'].
		". Signed up in: ".$user_row['first_signup'].".<br />";
		echo "<a data-userid='$user_result_id' data-name='".$user_row['username'].
		"' data-email='".$user_row['email']."' class='pending_user'>Admit</a></p>";
		
		$current_user += 1;
	}
	
	echo "</div></section>";
}

disconnect_sql($joke_connection);
?>

<script>
<?php
if ($_SESSION['user_power'] >= 1) {
    //ToDo: warn if any setting was changed.
    echo "function settings_pass(power) {
        $(\"section.settings\").stop().fadeOut();
        $(\"section#settings\" + power).fadeIn();
    }";
}
	
if ($_SESSION['user_power'] >= 2) {
	echo "$(document).ready(function() {
		$(\"section#settings2 div.pending_user a.pending_user\").click(approve_user);
	})";
}
</script>
