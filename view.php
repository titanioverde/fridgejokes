<html>
<head><title>Fridge Jokes</title>
<script src='./lib/view.js'></script>
</head>

<body>



<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}

//An invisible line to center objects.
echo "<div class='horizon joke_frame_ani_start'>";

//Behold the main joke frame!
echo "<div id=main_joke_div class='joke_frame main_joke'>";

//And a DIV with the current joke submitter nickname.
echo "<div id='submitter_name' class='joke_submitter'>&nbsp;</div>";

//But, before, the overlaying user actions DIV.
echo "<div id='user_actions_menu' class='user_actions'>";
echo "<p class='followup_link'><a href='#!send'><acronym title='Maybe you want to publish a followup joke, or just a reply, to this one?'>Send a followup</acronym></a></p>";
	echo "<div id='vote_menu'>";
	include "vote.php";
	echo "</div>";

	//Tweet button.
	//echo "<a id='tweet_button' href='https://twitter.com/share' class='twitter-share-button' data-url='http://titanioverde.koding.com/fridgejokes/index.php?joke=".$_GET['joke']."' data-text='On Fridge Jokes:' data-count='none' data-hashtags='persona4'>Tweet</a>
	//<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";

echo "</div>";

//And a overlaying Loading DIV for User Actions.
echo "<div id='user_actions_loading' class='user_actions user_actions_loading'><p></p></div>";

//Joke content, coming from AJAX.
echo "<div id=main_joke_text><p id=main_joke_p class='main_joke'>&nbsp;</p>";

//End of joke text div.
echo "</div>";

//A formal suggestion.
echo "<p class='another_joke'><a href='#!' onClick=\"read_joke()\"><acronym title='Read another random joke'>Next</acronym></a></p>";

//End of main div.
echo "</div>";

//Nice and Personish background DIV.
echo "<div id=main_joke_background class='joke_frame rear_frame'><p></p></div>";

//End of horizon.
echo "</div>";

//mysql_free_result($joke_result);
disconnect_sql($joke_connection);

?>

<script>
	$(document).ready(function () {
		read_joke(<?php print json_encode($_GET); ?>);
	});
</script>



</body>
</html>