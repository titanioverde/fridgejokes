<?php
//error_reporting(E_ALL);

include "../lib/common_sql.php";
$con = connect_sql();
session_start();


if (!(empty($_GET['joke']))) {
    $joke_ID = mysql_real_escape_string($_GET['joke']);
}
else {
    $joke_ID = 0; //Default. Random joke.
}
if (($_SESSION['user_id'] > 0) || (!empty($_SESSION['user_id']))) {
    $user_id = $_SESSION['user_id'];
}
else { $user_id = 0; }


//Sum view count to the current joke.
function add_view($joke) {
	$query = "UPDATE jokes SET views = (views + 1) WHERE joke_id = '$joke';";
	mysql_query($query);
	oh_my_error(mysql_error(), $query, $stop = false);
}

//Sum view count from this user.
function add_user_reads($user) {
	if ($user > 0) {
		$query = "UPDATE users SET jokes_read = (jokes_read + 1) WHERE user_ID = '$user';";
		mysql_query($query);
		oh_my_error(mysql_error(), $query, $stop = false);
	}
}

//Default query for one random joke.
$see_dislikes = "";
if (!$_SESSION['see_dislikes']) {
	$see_dislikes = "joke_ID NOT IN (SELECT joke_ID FROM dislikes WHERE user_ID = '$user_id') AND ";
}

$see_loved = "";
if ($_GET['favorites'] == 1) {
    $see_loved = "joke_ID IN (SELECT joke_ID FROM loves WHERE user_ID = '$user_id') AND ";
}

$order = "RAND()";
if ($_GET['visited'] == 1) {
    $order = "views DESC";
}
elseif ($_GET['visited'] == 2) {
    $order = "views ASC";
}
if ($_GET['recent'] == 1) {
    $order = "timestamp DESC";
}
//ToDo: most liked and most disliked.

$joke_query = "SELECT * FROM jokes WHERE status = 'A' AND ".$see_dislikes.$see_loved."(followup_ID = '0' OR followup_ID IS NULL) ORDER BY ".$order." LIMIT 1;";
if ($joke_ID > 0) {
    //Query for a specific joke.
    $joke_query = "SELECT * FROM jokes WHERE status='A' AND joke_ID = '$joke_ID';";
}

//Same MySQL process as always.
$joke_result = mysql_query($joke_query);
oh_my_error(mysql_error(), $joke_query);
if (mysql_num_rows($joke_result)) {
	$joke_row = mysql_fetch_array($joke_result);
	
	//Making received text a bit nicer.
	$joke_text = htmlspecialchars($joke_row['text']);
	$joke_arranged = str_replace("\n", "<br />", $joke_text); //Let's preserve line breaks!
	$joke_row['text'] = $joke_arranged;
	$submitter_info = submitter_id_to_name($joke_row['submitter']);
	$joke_row['submitter'] = $submitter_info[1];
	$joke_row['submitter_id'] = $submitter_info[0];
	
	//Send the resulting array in json format.
	header("Content-type: application/json");
	
	echo json_encode($joke_row);
	
	//Statistics and end.
	add_view($joke_row['joke_ID']);
	add_social_link(1, $_SESSION['user_id']);
	add_user_reads($user_id);
}
else {
	$joke_row['text'] = "&gt; You opened the fridge.<br />&gt; Nothing catches your eye.<br />(No joke found under these requirements. Our apologies.)";
	$joke_row['submitter'] = "Margaret";
	//$joke_row['submitter_id'] = -1;
	$joke_row['joke_ID'] = -1;
	
	//Send the resulting array in json format.
	header("Content-type: application/json");
	
	echo json_encode($joke_row);
}

disconnect_sql($con);
?>