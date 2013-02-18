<?php

include "common.php";
session_start();
$joke_connection = connect_sql();
session_variables();
check_power(1);

if ((!(empty($_GET['action']))) && (!(empty($_GET['joke'])))) {
	$joke_id = mysql_real_escape_string($_GET['joke']);
	$current_user_id = $_SESSION['user_id'];
	
	// To approve or to reject?
	if ($_GET['action'] == "approve") {
		$action = 1;
	}
	elseif ($_GET['action'] == "reject") {
		$action = -1;
	}
	else {
		die("Unknown moderating action. Kanji knows nothing about moderation.");
	}
	
	//Vote added as a row in moderate table.
	$moderation_query = "INSERT INTO moderate (joke_ID, user_ID, timestamp, action) VALUES ('$joke_id', '$current_user_id', UNIX_TIMESTAMP(), '$action');";
	mysql_query($moderation_query);
	oh_my_error(mysql_error(), $moderation_query);
	echo "<p>Vote applied.</p>";
	
	// If there are enough votes, publish.
	$votes_query = "SELECT SUM(action) FROM moderate WHERE joke_ID = $joke_id;";
	$votes_sum = single_select_query($votes_query, "SUM(action)");
	$max_votes = find_setting("max_moderation_votes");
	//print $max_votes." ".$votes_sum;
	
	if ($votes_sum >= $max_votes) {
		$new_status = "A";
		$result_string = "This joke is now published.";
	}
	elseif ($votes_sum <= ($max_votes * -1)) {
		$new_status = "D";
		$result_string = "This joke is now removed.";
	}
	else {
		$result_string = "No more changes were made.";
	}
	
	//Change joke status if needed.
	if (isset($new_status)) {
		$joke_ultimate_query = "UPDATE jokes SET status = '$new_status' WHERE joke_ID = '$joke_id';";
		mysql_query($joke_ultimate_query);
		oh_my_error(mysql_error(), $joke_ultimate_query);
		echo "<p class='moderation_result'>$result_string</p>";
	}
	//echo "<p><a onClick=\"main_pass('moderate.php')\">Turn back to the Moderation panel?</a></p>";
}

?>