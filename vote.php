<script src='lib/send.js'></script>
<?php
/*if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
//load_menu();

//For which joke? If there's no PHP variable, look at GET.
if (!isset($joke_ID)) {
    if (isset($_GET['joke'])) {
        if ($_GET['joke'] != "") {
			if ($_GET['joke'] == -1) {
				die("Votes unavailable.");
			} else {
				$joke_ID = mysql_real_escape_string($_GET['joke']);
			}
        }
    }
}

$user_ID = $_SESSION['user_id'];

//If I use messages from vote2 in the future.
$message = "";
if (!empty($_GET['message'])) {
    $message = $_GET['message'];
}

$votes_total = array(vote_count($joke_ID, "likes", null), vote_count($joke_ID, "likes", $user_ID),
                     vote_count($joke_ID, "dislikes", null), vote_count($joke_ID, "dislikes", $user_ID),
                     vote_count($joke_ID, "loves", null), vote_count($joke_ID, "loves", $user_ID));

//Add anonymous likes.
$anon_query = "SELECT likes_anon FROM jokes WHERE joke_id = '$joke_ID';";
$anon_likes = single_select_query($anon_query, "likes_anon");

//Did this user already vote?
$own_votes = array();
if ($votes_total[1]) $own_votes[0] = " class='like'";
if ($votes_total[3]) $own_votes[1] = " class='dislike'";
if ($votes_total[5]) $own_votes[2] = " class='love'";
*/

//Let me sing you the table of my people.
echo "<div id='vote_menu' class='vote_div'>
        <table class='vote_table'>
            <tbody>
                <tr>";
echo "              <td class='like' id='like_link'><a><span class='vote_symbol'>+1</span> Like (<span class='sum' id='likes_sum'>0</span>)</a></td>
                    <td class='dislike middle' id='dislike_link'><a><span class='vote_symbol'>-1</span> Dislike (<span class='sum' id='dislikes_sum'>0</span>)</a></td>
                    <td class='love' id='love_link'><a><span class='vote_symbol'>&lt;3</span> Love! (<span class='sum' id='loves_sum'>0</span>)</a></td>
                </tr>
            </tbody>
        </table>
		<p id='vote_result' style='display: none'></p>
    </div>";

?>

<script>
    $(document).ready(user_actions_effect(1));
    function pass_vote(link) {
        user_actions_effect(0);
        main_pass(link, "vote_menu", "no");
    }
	
</script>