<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}

$message = "Nothing happened."; //Default message.
$result = "nothing";
$current_user_id = $_SESSION['user_id'];

if ((isset($_GET['joke'])) && (isset($_GET['action']))) {
    $joke_id = mysql_real_escape_string($_GET['joke']);
    $action = mysql_real_escape_string($_GET['action']."s");
	
    //Likes from guests.
	if ($current_user_id == 0) { 
		if ($action != "likes") {
			$message = "As a guest user, you are only allowed to vote \"Like\". You should sign up as a genuine user.";
			$result = "not_for_guest";
		}
		else {
			$likes_anon_query = "SELECT likes_anon, last_like_anon FROM jokes WHERE joke_ID = '$joke_id';";
			$likes_anon = single_select_query($likes_anon_query);
			if ($likes_anon['likes_anon'] >= find_setting("max_anon_likes")) { //Did too many anonymous like this?
				$message = "We allow no more votes from anonymous MCs. You should register yourself as an user.";
				$result = "anon_limit";
			}
			else {
				if (($likes_anon['last_like_anon'] + find_setting("cooldown_anon_likes")) >= time(u)) { //Did the last anonymous vote just a while ago?
					$message = "Some anonymous MC already voted recently. We request you to wait some minutes.";
					$result = "anon_cooldown";
				}
				else {
					$likes_query = "UPDATE jokes SET likes_anon = (likes_anon + 1), last_like_anon = UNIX_TIMESTAMP() WHERE joke_ID = '$joke_id';";
					$message = "Vote submitted. Now you understand the world around you a bit better.";
					$result = "success";
				}
			}
		}
	}
	//Votes from registered users.
	else {
		if (vote_count($joke_id, $action, $current_user_id) == 0) { //Not voted before.
			$likes_query = "INSERT INTO $action (user_ID, joke_ID, timestamp) VALUES ('$current_user_id', '$joke_id', UNIX_TIMESTAMP());";
			$message = "Vote submitted.";
			$result = "success";
		}
		else { //Voted before.
			$likes_query = "DELETE FROM $action WHERE joke_ID = '$joke_id' AND user_ID = '$current_user_id';";
			$message = "Vote discarded.";
			$result = "discard";
		}
	}
	if (!empty($likes_query)) {
		mysql_query($likes_query);
		oh_my_error(mysql_error(), $likes_query);
	}

}		

//AJAX output.
header("Content-type: application/json");
$json_return = array("message" => $message, "result" => $result);
echo json_encode($json_return);
//echo "<p class='vote_message'>$message</p>";

//echo "<script>joke_id = $joke_id</script>";

/*<script>
	
$(document).ready(function() {
    user_actions_effect(1);
	back_to_vote = setTimeout(function() {
		user_actions_effect(0);
		read_votes(joke_id);
		//main_pass("vote.php", "vote_menu", "no");
	}, 3000);
    
});
</script>*/
?>