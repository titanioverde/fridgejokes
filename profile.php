<?php
if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}

// For which user? Error if not specified.
if (empty($_GET['user'])) {
	die("<p>No user specified</p>");
}
$user = mysql_escape_string($_GET['user']);
if ($user == 0) {
	die("<p>This was related to a guest, and not an actual user.</p>");
}

//Almost the same query process as ever
$query = "SELECT username, location, bio, website FROM users WHERE user_ID = '$user';";
$row = single_select_query($query);

if (empty($row['location'])) $row['location'] = "<span class='no_profile'>Location unknown.</span>";
if (empty($row['website'])) $row['website'] = "<span class='no_profile'>No URL registered.</span>";
if (empty($row['bio'])) $row['bio'] = "<span class='no_profile'>No bio yet.</span>";


//Contents.
echo "<article class='profile'>
	<header>".$row['username']."</header>
	<p class='location'>".$row['location']."</p>
	<p class='website'>".$row['website']."</p>
	<p class='bio'>".$row['bio']."</p>
</article>";

?>

