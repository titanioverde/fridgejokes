<script>
	//Animations for the menu bar.
	function open_menu() {
		$(".menu").css("visibility", "visible");
		new_height = parseInt($("nav#menu_front p:first").css("height")) + (parseInt($("nav#menu_front p:first").css("margin-top")) + parseInt($("nav#menu_front p:first").css("margin-bottom"))) / 2 + "px";
		//new_top = parseInt($("nav#menu_front a:first").position().top) - parseInt($("nav#menu_front p:first").css("margin-top")) / 2 + "px";
		$("div#menu_selection_bar").css("height", new_height);
		$("div#menu_selection_bar").css("top", -50).show();
		$("div.to_close_menu").css("visibility", "visible");
		$("div#menu_rear").removeClass("menu_rear_ani_end");
		$("nav#menu_front").removeClass("menu_front_ani_end");
		$("div#menu_rear").addClass("menu_rear_ani_start");
		$("nav#menu_front").addClass("menu_front_ani_start");
	}
	
	function close_menu() {		
		$("div.to_close_menu").css("visibility", "hidden");
		$("div#menu_selection_bar").slideUp(125);
		if ($(".menu").css("visibility") == "visible") {		
			$("div.to_close_menu").css("visibility", "hidden");
			$("div#menu_rear").removeClass("menu_rear_ani_start");
			$("nav#menu_front").removeClass("menu_front_ani_start");
			$("div#menu_rear").addClass("menu_rear_ani_end");
			$("nav#menu_front").addClass("menu_front_ani_end");
			setTimeout(function () {
				$(".menu").css("visibility", "hidden");
				}, 500);
		}
		$("div.shadow").fadeOut().addClass("hidden");
	}
	
	//Change the content of the main DIV, Novice AJAX Style.
	function main_pass(link, div, loading) {
		// $("html").removeClass("-o- -webkit- -moz- -ms-"); //Clean some style garbage from prefixfree. Remove this line when I find a better solution.
		div = div || "the_world";
		loading = loading || "yes";
		/*$(document).off("keydown", "**");*/
		$.ajax(link, {
			success: function(data) {
				$("div#" + div).html(data);
				$("div#loading").fadeOut(250);
				setTimeout(function () {
					$("div#" + div).css("overflow", "auto");
				}, 2000);
			},
			beforeSend: function() {
				if (loading == "yes") $("div#loading").fadeIn(250);
				$("div#" + div).css("overflow", "hidden");
				close_menu();
			},
		});
	}
	
	//Show a secondary preloaded DIV, and to close the menu bar if necessary.
	function main_shadow(link) {
		close_menu();
		$("div#shadow_" + link).fadeIn().removeClass("hidden");	
		$("div.to_close_menu").css("visibility", "visible");
	}
	
	//Fill (through argument) and show a certain shadow DIV for small alerts.
	function show_myalert(data) {
		target = "div#myalert";
		background = "div.to_close_menu";
		$(target).html(data).fadeIn();
		$(background).css("visibility", "visible");
	}
	
	//Fill and show the profile DIV. Contents from profile.php.
	function show_profile(user) {
		if (user == 0) {
			alert("This user is not registered.");
		}
		else {
			target = "div#profile";
			background = "div.to_close_menu";
			$.ajax("profile.php", {
				data: {user: user},
				type: "get",
				dataType: "html",
				timeOut: 10000,
				success: function(data) {
					$(target).html(data).fadeIn().removeClass("hidden");
					$(background).css("visibility", "visible");
				},
			});
		}
	}

</script>

<?php

//Menu layers.
$notyet = "javascript:alert('NotOpenYet')";

echo "<div class='menu_button' id='menu_button' onClick='open_menu()'><p>MENU</p></div>";

echo "<div class='to_close_menu' id='to_close_menu' onClick='close_menu()'><p style='text-align: right'><span class='miniwarning'>(Click anywhere outside to close this window)</span></p></div>";

echo "<div class='menu menu_rear menu_ani_common' id='menu_rear'><p></p></div>";

echo "<div class='menu menu_selection' id='menu_selection_bar'><p>&nbsp;</p></div>";

//--- Menu contents
echo "<nav class='menu menu_front menu_ani_common' id='menu_front'>";
//echo "<p>".($_SESSION['user_id'] > 0)."</p>";
if ($_SESSION['user_id'] > 0) {
	echo "<span id='current_user' class='miniwarning'>Logged in as: ".$_SESSION['current_username']."</span>";
}
if ($_SESSION['user_power'] > 1) {
	echo "Registered users: ".single_select_query("SELECT COUNT(*) FROM users", "COUNT(*)");
}
echo "<p id='view' onClick=\"main_pass('view.php')\"><a href='#'>View random jokes</a></p>
	<p id='recent' onClick=\"main_pass('view.php?recent=1')\"><a href='#'>Newest joke</a></p>
	<p id='most_visited' onClick=\"main_pass('view.php?visited=1')\"><a href='#'>Most visited joke</a></p>
	<p id='less_visited' onClick=\"main_pass('view.php?visited=2')\"><a href='#'>Less visited joke</a></p>
	<p id='send' onClick=\"main_pass('send.php')\"><a href='#'>Send a joke</a></p>";
if ($_SESSION['user_power'] >= 1) {
	echo "<p id='moderate' onClick=\"main_pass('moderate.php')\"><a href='#'>Moderation room</a></p>";
}
echo "<p id='faq' onClick=\"main_shadow('faq')\"><a>F.A.Q.</a></p>";

$last_feature = date("m/d", single_select_query("SELECT timestamp FROM developer_log ORDER BY timestamp DESC LIMIT 1;", "timestamp"));
echo "<p id='dev_log' onClick=\"main_shadow('log')\"><a>Recent changes <span class='miniwarning'>($last_feature)</span></a></p>";
if ($_SESSION['user_id'] == 0) {
	echo "<p id='login' onClick=\"main_shadow('login')\"><a>Login</a></p>";
	echo "<p id='signup' onClick=\"main_shadow('signup')\"><a>Signup</a></p>";
}
else {
	echo "<p id='settings' onClick=\"main_pass('settings.php')\"><a href='#'>Settings</a></p>";
	echo "<p id='logout' href='logout.php'><a href='logout.php'>Logout</a></p>";
}
echo "</nav>";
//Menu contents ---

?>

<script>
	//Menu rendered. Selection box handler ON!
	$(document).ready(function () {
		$("nav#menu_front p").mouseenter(move_selection);
	});
</script>
