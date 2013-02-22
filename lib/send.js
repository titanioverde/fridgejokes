/* Fridge Jokes no he */

//From send.php. Form data is serialized and sent through POST.
function submit_joke() {
	submit_button = "input#joke_submit";
    $(submit_button).click(function(event) {
        $.ajax("send2.php", {
            dataType: "html",
            type: "post",
            data: $(this.form).serialize(),
            success: function(data) {
                show_myalert(data);
				$(submit_button).removeAttr("disabled");
				//$(submit_button.form).clearForm(); ToDo
				now_sending(1);
				$("textarea").attr("value", "> You opened the fridge.\r\n> ");
            },
			beforeSend: function() {
				$(submit_button).attr("disabled", "disabled");
				now_sending(0);
				
			},
			error: function() {
				$("div#result").text("> You should probably try again.");
				$(submit_button).removeAttr("disabled");
				now_sending(1);
			},
			timeOut: 15000,
        });
        
        event.preventDefault();
    });
}

//Just to show a little Loading DIV during an AJAX call.
function now_sending(state) {
	father = "textarea#joke_text_form";
	target = ".sending";
	if (state == 0) { //loading
		/*$(target).css({
			height: $(father).css("height"),
			width: $(father).css("width"),
			left: $(father).css("left"),
			top: $(father).css("top") }); */
		$(target).fadeIn("fast");
	}
	if (state == 1) { //success or error
		$(target).fadeOut("slow");
	}
}

function get_size_and_location(target, father) {
	//ToDo
}

//From moderate.php. This data doesn't need to be serialized. GET.
function send_moderate(e) {
	joke_id = $(this).data("jokeid");
	action = $(this).data("action");
	$.ajax("moderate2.php", {
		dataType: "html",
		type: "get",
		data: {
			joke: joke_id,
			action: action,
		},
		success: function(data) {
			show_myalert(data);
			//$("div#result").html(data);
			now_sending(1);
			$("article#pending" + joke_id).slideUp("slow").remove();
		},
		beforeSend: function() {
			$("div#result").text(action);
			now_sending(0);
		}
	});

}

function approve_user(e) {
	user_id = $(this).data("userid");
	name = $(this).data("name");
	email = $(this).data("email");
	$.ajax("newuser.php", {
		dataType: "html",
		type: "post",
		data: {
			user: user_id,
			name: name,
			email: email,
		},
		success: function(data) {
			show_myalert(data);
			$("div.pending_user p#user" + user_id).remove();
		},
	});
}

function send_vote(joke_id, action) {
	joke_id = joke_id;
	action = action;
	sleed = 250; //Miliseconds for every animation on the Results text.
	$.ajax("vote2.php", {
		dataType: "json",
		type: "get",
		data: {
			joke: joke_id,
			action: action,
		},
		success: function (data) {
			$("#vote_result").stop().text(data.message).slideDown(sleed).delay(5000).slideUp(sleed).text("");
			target = "#" + action + "_link";
			console.log(data.result);
			switch (data.result) {
				case "success":
					$(target + " a").addClass(action);
					$(target + " span.sum").text(parseInt($(target + " span.sum").text()) + 1);
					break;
				case "discard":
					$(target + " a").removeClass(action);
					$(target + " span.sum").text(parseInt($(target + " span.sum").text()) - 1);
					break;
				default:
					break;
			}
		},
		beforeSend: function () {
			$("#vote_result").css("height", "auto").stop().text("Submitting vote...").slideUp(sleed);
		},
		error: function () {
			$("#vote_result").text("Votes unavailable. We apologize.").slideDown(sleed).delay(5000).slideUp(sleed).text("");
		},
		timeout: 15000,
	});
}
