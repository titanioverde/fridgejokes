<html>
<head><title>Fridge Jokes</title>
<script src='./lib/send.js'></script>
</head>

<body>
<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
//load_menu();
check_power(1);
$user_id = $_SESSION['user_id'];

//Limited view for everyone able, except for super-admin.
$moderator_restriction = "";
if ($_SESSION['user_power'] < 3) {
	$moderator_restriction = " AND submitter != '$user_id' AND joke_ID NOT IN (SELECT joke_ID FROM moderate WHERE user_ID = '$user_id');";
}

//Jokes with Pending ("P") status.
$pending_jokes_query = "SELECT * FROM jokes WHERE status='P'$moderator_restriction;";
$pending_jokes_result = mysql_query($pending_jokes_query);
oh_my_error(mysql_error(), $pending_jokes_query);
$max_jokes = mysql_num_rows($pending_jokes_result);

//Main DIV.
echo "<div id='moderation_panel' class='country'>";
if ($max_jokes == 0) {
    die("<p>There are no pending jokes to moderate. Surely Nanako would like to watch TV with you.</p>");
}
else {
    $joke_number = 0;
    while ($joke_number < $max_jokes) {
    //To list every joke with status P.
		$current_joke = mysql_fetch_array($pending_jokes_result);
		$joke_text = htmlspecialchars($current_joke['text']); //ToDo: common function to clean jokes' format.
		$joke_submitter = htmlspecialchars($current_joke['submitter']);
		$joke_submitter = submitter_id_to_name($joke_submitter);
		$joke_id = $current_joke['joke_ID'];
		
		//Contents and actions for this joke to HTML.
		echo "<article class='moderation pending_joke' id='pending$joke_id'>";
		echo "<p class='moderation content'>ID: $joke_id. Content: $joke_text</p>";
		echo "<p class='moderation submitter'>Submitter: $joke_submitter[1]</p>";
		//echo "<p><a onClick=\"main_pass('send.php?joke=$joke_id')\">Edit</a> - <a onClick=\"main_pass('moderate2.php?action=approve&joke=$joke_id')\" data-jokeid='$joke_id' data-action='approve'>Approve</a> - <a onClick=\"main_pass('moderate2.php?action=reject&joke=$joke_id')\" data-jokeid='$joke_id' data-action='reject'>Reject</a></p>";
		echo "<p class='moderation actions'><a onClick=\"main_pass('send.php?joke=$joke_id')\">Edit</a> - <a onClick=\"send_moderate()\" data-jokeid='$joke_id' data-action='approve' class='moderation_vote'>Approve</a> - <a onClick=\"send_moderate()\" data-jokeid='$joke_id' data-action='reject' class='moderation_vote'>Reject</a></p>";
		
		//End of joke.
		echo "</article>\n";
		$joke_number += 1;
    }
}

//Loading DIV.
echo "<div class='user_actions_loading sending'></div>";

//echo "<div id='result'>_</div>";

//End of general DIV.
echo "</div>";

mysql_free_result($pending_jokes_result);
mysql_close($joke_connection);

?>

<script>
	//Handler for votes.
	$(document).ready(function () {
		$("a.moderation_vote").click(send_moderate);
	})
</script>