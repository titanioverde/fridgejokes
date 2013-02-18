<script>
	function open_menu() {
		$(".menu").css("visibility", "visible");
		$("div.to_close_menu").css("visibility", "visible");
		$("div#menu_rear").removeClass("menu_rear_ani_end");
		$("nav#menu_front").removeClass("menu_front_ani_end");
		$("div#menu_rear").addClass("menu_rear_ani_start");
		$("nav#menu_front").addClass("menu_front_ani_start");
	}
	
	function close_menu() {
		$("div#menu_rear").removeClass("menu_rear_ani_start");
		$("nav#menu_front").removeClass("menu_front_ani_start");
		$("div#menu_rear").addClass("menu_rear_ani_end");
		$("nav#menu_front").addClass("menu_front_ani_end");
		setTimeout(function () {
			$(".menu").css("visibility", "hidden");
			$("div.to_close_menu").css("visibility", "hidden");
			}, 500);
		
	}
</script>

<?php

$notyet = "javascript:alert('NotOpenYet')";

echo "<div class='menu_button' id='menu_button' onClick='open_menu()'><p>MENU</p></div>";

echo "<div class='to_close_menu' id='to_close_menu' onClick='close_menu()'><p></p></div>";

echo "<div class='menu menu_rear menu_ani_common' id='menu_rear'><p></p></div>";

echo "<nav class='menu menu_front menu_ani_common' id='menu_front'>";
if ($_SESSION['user_id'] != 0) {
	"<p id='current_user'>Logged in as: ".$_SESSION['user_id']."</p>";
}
echo "<p id='view'><a href='view.php'>View jokes</a></p>
	<p id='loved'><a href=$notyet>Your favorite ones</a></p>
	<p id='send'><a href='send.php'>Send a joke</a></p>";
if ($_SESSION['user_power'] >= 1) {
	echo "<p id='moderate'><a href='moderate.php'>Moderation room</a></p>";
}
if ($_SESSION['user_id'] == 0) {
	$logword = "login";
}
else {
	$logword = "logout";
}
echo "<p id='settings'><a href='settings.php'>Settings</a></p>
	<p id='$logword'><a href='$logword.php'>$logword</a></p>
</nav>";

?>

