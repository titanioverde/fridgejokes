<html>
<head><title>Fridge Jokes</title>
<script src='./lib/send.js'></script>
</head>

<body>

<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}
//load_menu();

//If joke number specified, edit it. Else, it's a new one.
if ($_GET['joke'] != "") {
    check_power(1);
    $edit_mode = true;
    $joke_id = mysql_real_escape_string($_GET['joke']);
    $joke_query = "SELECT text, submitter, spoiler FROM jokes WHERE joke_ID = '$joke_id';";
    $joke_row = single_select_query($joke_query);
    $joke_text = $joke_row['text'];
    $submitter = $joke_row['submitter'];
	$spoiler = $joke_row['spoiler'];
    
}
else {
    $edit_mode = false;
    $joke_text = "&gt;&nbsp;You opened the fridge.&#10;&gt;&nbsp;";
    $submitter = $_SESSION['current_username'];
	$spoiler = 0;
}

//Form.
echo "<form action='send2.php' method='post' charset='utf-8' id='joke_form' class='country send_form'>
    <fieldset>
        <label for='joke_text_form'>Joke goes here:<br /></label>
        <textarea rows=5 cols=60 name='joke_text' id='joke_text_form'>$joke_text</textarea>
    </fieldset>";
    
    
    if (!($edit_mode)) {
       echo "<fieldset>
        <label for='submitter_form'>Author&#39;s name: <span class='miniwarning'>Leave it as is if this joke comes from your own Expression.</span><br/></label>";
        echo "<input type='text' maxlength=24 name='submitter' id='submitter_form' placeholder='$submitter' />";
    }    

    if ($_SESSION['user_id'] == 0) {
        //echo "<p>(Place to control you're a human)</p>";
    }
    
    if ($edit_mode) {
        echo "<input type='hidden' name='joke_id' value='$joke_id' />";
    }
    
    if ($_GET['followup'] != "") {
        echo "</fieldset>\n<fieldset>";
        echo "<p>This joke will be following up another one.</p>";
        echo "<input type='hidden' name='followup' value='".mysql_real_escape_string($_GET['followup'])."' />";
    }

//Loading DIV
echo "<div class='user_actions_loading sending'></div>";
?>
        
    </fieldset>
    <fieldset>
        <label for='spoiler_form'><abbr title='Check this if your joke is not suitable for people who are yet to play Persona4 and 3 first editions.'>Could it be a spoiler?</abbr></label>
        <input type='checkbox' name='spoiler' id='spoiler_form' <?php if ($spoiler) echo "checked='checked'"; ?>/>
    </fieldset>   
    
    <fieldset>
        <input type='submit' id='joke_submit' value='Talk to the fridge' />
    </fieldset>
</form>

<div id='result'></div>

<script>
	//To change submit button function.
    $(document).ready(function() {
        submit_joke();
    });
</script>

</body>
</html>