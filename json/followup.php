<?php
//error_reporting(E_ALL);

include "../lib/common_sql.php";
$con = connect_sql();
session_start();

$joke_ID = 0;
if (!(empty($_GET['joke']))) {
    $joke_ID = mysql_real_escape_string($_GET['joke']);
}
else {
    die(); //Joke ID required.
}

$joke_query = "SELECT joke_ID FROM jokes WHERE followup_ID = '$joke_ID' AND status = 'A' ORDER BY timestamp ASC;";

$joke_result = mysql_query($joke_query);
oh_my_error(mysql_error(), $joke_query);

$joke_return = array();

while ($joke_row = mysql_fetch_row($joke_result)) {
	array_push($joke_return, $joke_row[0]);
}

//Send the resulting array in json format.
header("Content-type: application/json");

echo json_encode($joke_return);

disconnect_sql($con);
?>