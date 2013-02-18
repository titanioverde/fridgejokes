<?php
session_start();
include("common.php");
$joke_connection = connect_sql();
session_variables();

$joke_text = mysql_real_escape_string($_POST['joke_text']);
if (empty($joke_text)) {
    die("Your submitted joke seems to be empty.");
}

if ($_POST['submitter'] == "") {
    $submitter = $_SESSION['user_id'];
    if ($submitter == 0 || empty($submitter)) $submitter = "Anonymous MC";
}
else {
    $submitter = mysql_real_escape_string($_POST['submitter']);
}

if ($_POST['spoiler'] == "checked") {
    $spoiler = 1;
}
else {
    $spoiler = 0;
}

$current_time = time("u");

if ($_POST['joke_id'] != "") {
    $joke_id = $_POST['joke_id'];
    $query_joke = "UPDATE jokes SET text = '$joke_text' WHERE joke_ID = '$joke_id';";
    $debug_result = "update";
}
else {
    $it_exists = single_select_query("SELECT joke_ID FROM jokes WHERE text = '$joke_text';", "joke_ID");
    if (empty($it_exists)) {
        $followup = 0;
        if ($_POST['followup'] != ""){
            $followup = $_POST['followup'];
        }
        
        $query_joke = "INSERT INTO jokes (status, text, submitter, timestamp, spoiler, followup_ID) VALUES ('P', '$joke_text', '$submitter', '$current_time', '$spoiler', '$followup')";
        $debug_result = "insert";
    }
    else {
        die("It seems that this joke already exists. We're afraid you tried to send it twice.");
    }
}

mysql_query($query_joke);
$sql_error = mysql_error();
oh_my_error($sql_error, $query_joke);
if ($sql_error != "") {
    echo "Joke not sent. It seems we must plug the fridge again.";
}
else {
    echo "Joke sent. If you have been a good player, a moderator will publish it soon.<br />Stay tuned!";
}

?>

