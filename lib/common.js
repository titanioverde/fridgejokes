

function show_myalert(data){
	target = "div#myalert";
	background = "div.to_close_menu";
	$(target).html(data).fadeIn();
	$(background).css("visibility", "visible");
}

function move_selection(e) {
	target = "div#menu_selection_bar";
	new_top = parseInt($(this).position().top) + parseInt($(this).css("margin-top")) / 2 + "px";
	$(target).stop().animate({
		top: new_top }, 125);
}

function space_pass(e) {
	$(document).keydown(function(e) {
		if ($("div#main_joke_div").length) {
			if (e.which == 32) {
				read_joke();
			}
		}
	});
}

function show_hide_player() {
	$("div#tune_player").toggleClass("tune_player_hide").toggleClass("tune_player_show");
}