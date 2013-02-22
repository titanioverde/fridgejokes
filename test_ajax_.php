<?php
error_reporting(E_ALL);

//include "common.php";
session_start();
//$joke_connection = connect_sql();
//session_variables();


if (empty($_SESSION['user_id'])) {
	echo "<div id='shadow_login' class='shadow hidden login'>";
	include "login.php";
	echo "</div>";
}

//include "headermenu_ajax.php";

?>

<div id='the_world' class='the_world'>
	<p><img src='Persona4_loading.gif'></p>
</div>

<div id='loading' class='loading'>
	<p></p>
</div>


<script type="text/javascript">
	$.ajax("index.html", {
		success: function (data) {
			$("div#the_world").html(data);
		},
	});
</script>