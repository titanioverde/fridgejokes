<?php
error_reporting(E_ALL);

include "common.php";
session_start();
$joke_connection = connect_sql();
session_variables();

$css_file = "./default.css";

?>

<link rel='stylesheet' href='<?php echo $css_file ?>'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
<script src='./prefixfree.min.js'></script>
<script src='lib/common.js'></script>


<?php

if (empty($_SESSION['user_id'])) {
	echo "<div id='shadow_login' class='shadow hidden login'>";
	include "login.php";
	echo "</div>";
	
	echo "<div id='shadow_signup' class='shadow hidden signup'>";
	include "signup.php";
	echo "</div>";
}

echo "<div id='shadow_log' class='faq shadow hidden'>";
include "developer_log.php";
echo "</div>";

echo "<div id='shadow_faq' class='faq shadow hidden'>";
include "faq.html";
echo "</div>";

include "headermenu_ajax.php";


if ($_SESSION['tune_player'] > -1) {
	echo "<div id='tune_player' class='tune_player menu_ani_common tune_player_hide' onClick='show_hide_player()'>
		<table>
			<tr>
				<td class='tune_player'><iframe width='376' height='190' src='http://www.youtube.com/embed/QR4AGuQT-g4?list=PL4HqDRVVxfT33nsrKX0dFoLtnWGwCbbiz&amp;";
				if ($_SESSION['tune_player'] == 0) echo "autoplay=0"; else echo "autoplay=1";
				echo "&amp;autohide=0&amp;fs=0&amp;loop=1&amp;theme=light&amp;version=3' frameborder='0'></iframe></td>
				<td class='hide_button'><p>&#x266B;</p></td>
			</tr>
		</table>";
	
	if ($_SESSION['user_id'] == 0) {
		echo "<div id='tune_player_warning' class='tune_player_warning'>
			<p>&#x27F5;&nbsp;Music incoming!</p>
		</div>";
	}
}
?>
</div>

<div id='the_world' class='the_world'>
	<p><img src='Persona4_loading.gif'></p>
</div>

<div id='loading' class='loading'>
	<p></p>
</div>

<div id='myalert' class='shadow hidden'>
	<p></p>
</div>

<div id='profile' class='shadow hidden'>
	<p></p>
</div>



<script type="text/javascript">
	//ToDo: change this page based on document.location.hash.
	
	/*$.ajax("view.php", {
		success: function (data) {
			$("div#the_world").html(data);
		}
	});*/
	;
	
	$(document).ready(main_pass('view.php<?php if (!empty($_GET['joke'])) echo "?joke=".$_GET['joke']; ?>'));
	space_pass();
	
	$("div#tune_player_warning").ready(function() {
		setTimeout(function() {
			$("div#tune_player_warning").remove();
		}, 10000);
	});
	
</script>