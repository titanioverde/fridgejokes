<?php

// Everything needed for a healthy and balanced database connection.
function connect_sql()
{
	$con = mysql_connect("mysql0.db.koding.com","titanioverde_1f8","satonaka") or die(mysql_error()); //ToDo: CRYPT THIS PASSWORD!
	mysql_select_db("titanioverde_1f8") or die(mysql_error());
	mysql_set_charset("utf8");
	return $con;
}

function disconnect_sql($con)
{
	mysql_close($con);
}

//I'M SO TIRED! Maybe I had to get this function done a lot of time ago.
//ToDo: specify connection (despite I usually only open one)
function single_select_query($query, $field = "none") {
	$result = mysql_query($query);
	oh_my_error(mysql_error(), $query);
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	if ($field != "none") {
		return $row[$field];
	}
	else {
		return $row; //As an array.
	}
}

// Saves errors from SQL and their whole queries. It stops page processing by default.
function oh_my_error($error, $query, $stop = true) {
	if ($error) {
		print $error;
		$log_file = fopen("./sql_errors.log", "a");
		fwrite($log_file, date("d/m/Y H:i:s")." - ".$error."\n".date("d/m/Y H:i:s")." - ".$query."\n");
		fclose($log_file);
		if ($stop) die();
	}
}

//Leave it as is if it was saved with another name. Else, search from its ID.
function submitter_id_to_name($submitter) {
	$submitter_name = $submitter_id = $submitter;
	if ((0 < $submitter) && ($submitter < 9999)) {
		$submitter_query = "SELECT username FROM users WHERE user_ID = '$submitter';";
		$submitter_name = single_select_query($submitter_query, "username");
		
	}
	else {
		$submitter_id = 0;
	}
	if (empty($submitter_name)) $submitter_name = "Anonymous MC";
	return array($submitter_id, $submitter_name);
}

//Feature not yet implemented.
function add_social_link($points, $user) {
	if ($social_link) {
		if ($user > 0) {
			$query = "UPDATE users SET social = (social + '$points') WHERE user_ID = '$user';";
			mysql_query($query);
			oh_my_error(mysql_error(), $query, $stop = false);
		}
	}
}



?>