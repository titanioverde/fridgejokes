<?php
error_reporting(E_ALL);

include "./lib/common_sql.php";
$con = connect_sql();

$joke_query = "SELECT * FROM jokes WHERE status = 'A' AND (followup_ID = '0' OR followup_ID IS NULL) ORDER BY RAND() LIMIT 1;";
if (!(empty($_GET['joke']))) {
    $joke_ID = mysql_real_escape_string($_GET['joke']);
    $joke_query = "SELECT * FROM jokes WHERE status='A' AND joke_ID = '$joke_ID';";
}
$joke_result = mysql_query($joke_query);
oh_my_error(mysql_error(), $joke_query);
$joke_row = mysql_fetch_array($joke_result);

header("Content-type: application/json");

echo json_encode($joke_row);

mysql_close($con);
?>