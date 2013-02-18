<?php
//To give, as a JSON array, number of likes, dislikes and love, and if current user voted any of them.

error_reporting(E_ALL);

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

//Votes sums array, with no user.
$votes_total = array("likes" => vote_count($joke_ID, "likes", null), "like_own" => 0,
                     "dislikes" => vote_count($joke_ID, "dislikes", null), "dislike_own" => 0,
                     "loves" => vote_count($joke_ID, "loves", null), "love_own" => 0);

//Add anonymous likes.
$anon_query = "SELECT likes_anon FROM jokes WHERE joke_id = '$joke_ID';";
$anon_likes = single_select_query($anon_query, "likes_anon");
$votes_total['likes'] += $anon_likes;

//Logged in user? Check if he has voted or not (1 = true). Else, they're 0 by default.
if ($user_id) {
	$votes_total['like_own'] = vote_count($joke_ID, "likes", $user_id);
	$votes_total['dislike_own'] = vote_count($joke_ID, "dislikes", $user_id);
	$votes_total['love_own'] = vote_count($joke_ID, "loves", $user_id);
}

header("Content-type: application/json");
echo json_encode($votes_total);

disconnect_sql($con);
?>