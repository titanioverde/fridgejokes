/* Fridge Jokes no he */

var calling = null;
//To get one joke data in json format, to show it in the main DIV and to update related links.
function read_joke(get) {
	get = get || "";
	//clearTimeout(back_to_vote); ToDo
	if (calling) calling.abort();
	calling = $.ajax("./json/read_joke.php", {
		data: get,
		dataType: "json",
		success: function (data) {
			$("div#main_joke_text p.main_joke").stop().html(data.text).animate({ opacity: 1, }, 250);
			if (data.submitter_id != 0) {
				submitter_tag = "<p class='linkable' onClick='show_profile(" + data.submitter_id + ")'>" + data.submitter + "</p>";
			}
			else {
				submitter_tag = "<p>" + data.submitter + "</p>";
			}
			$("div#submitter_name").stop().html(submitter_tag).animate({ opacity: 1, }, 250);
			joke_id = data.joke_ID;
			$("div#user_actions_menu p.followup_link a").on("click", function (event) {
				main_pass('send.php?followup=' + joke_id);
			});
			calling = null;
			read_votes(joke_id);
		},
		beforeSend: function () {
			user_actions_effect(0);
			$("div#main_joke_text p.main_joke").animate({ opacity: 0, }, 250);
			$("div#submitter_name").animate({ opacity: 0, }, 250);
		}
	});
	
}

//To get one joke data in json format, but for simpler pages.
function simple_read_joke(get, div) {
	get = get || "";
	div = div || "div#joke_text";
	if (calling) calling.abort();
	calling = $.ajax("./json/read_joke.php", {
		data: get,
		dataType: "json",
		success: function (data) {
			$(div).html(data.text + "<br />Submitted by: " + data.submitter);
		}
	});
}

//Update links and totals of the current joke.
function read_votes (joke_id) { 
	$.ajax("json/read_votes.php", {
		data: {
			joke: joke_id,
		},
		cache: false,
		dataType: "json",
		success: function (data) {
			$("#likes_sum").text(data.likes);
			$("#like_link").off("click").on("click", function() { send_vote(joke_id, "like") });
			if (data.like_own == 1) $("#like_link").addClass("like"); else $("#like_link").removeClass("like");
			$("#dislikes_sum").text(data.dislikes);
			$("#dislike_link").off("click").on("click", function() { send_vote(joke_id, "dislike"); });
			if (data.dislike_own == 1) $("#dislike_link").addClass("dislike"); else $("#dislike_link").removeClass("dislike");
			$("#loves_sum").text(data.loves);
			$("#love_link").off("click").on("click", function() { send_vote(joke_id, "love") });
			if (data.love_own == 1) $("#love_link").addClass("love"); else $("#love_link").removeClass("love");
			user_actions_effect(1);
			search_followups(joke_id);
		},
		beforeSend: function () {
			user_actions_effect(0);
		}
	});
}

function search_followups (joke_id) {
	joke_id = joke_id;
	var follow_list = new Array();
	calling = $.ajax("./json/followup.php", {
		data: {
			joke: joke_id,
		},
		
		dataType: "json",
		success: function (data) {
			if (!($.isEmptyObject(data))) {
				$("div#main_joke_text p.main_joke").append("<p id='read_followups' class='read_followups'><a>Followups available! Search them?</a></p>");
				$("div#main_joke_text p#read_followups").click(function() { read_followups(data) });
			}
		},
	});
	
}

function read_followups (follow_list) {
	for (var i in follow_list) {
		$("div#main_joke_text p#read_followups").slideUp("fast").remove();
		joke_id = follow_list[i];
		//alert(i + " " + joke_id);
		$.ajax("./json/read_joke.php", {
			data: {
				joke: joke_id,
			},
			dataType: "json",
			success: function (data) {
				$("div#main_joke_text p.main_joke").append("<p class='followup'><span class='miniwarning'>Reply from " + data.submitter + ":</span><br />" + data.text + "</p>").fadeIn();
			},
		});
	}
}

//jQuery animations when asking for an action from the user little menu. Mainly to show and hide its loading DIV while covering the own menu.
function user_actions_effect(state) {
	target = "div#user_actions_loading";
	father = "div#user_actions_menu";
	if (state == 1) { //success
		//alert("Done: " + $(father).css("height") + " " + $(target).css("height"));
		$(target).stop().animate({
			height: $(father).css("height")
		}, 125, function () {
			$(father).show();
			$(target).fadeOut(250);
		});	
	}
	if (state == 0) { //loading
		$(target).css("height", $(father).css("height")).fadeIn(250, function() { $(father).hide(); });
		//alert("Loading: " + $(father).css("height") + " " + $(target).css("height"));
		
	}
}


