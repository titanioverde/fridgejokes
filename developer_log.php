

<?php

if (empty($joke_connection)) {
	include "./lib/common_sql.php";
	//session_start();
	//session_variables();
	$joke_connection = connect_sql();
}

$query = "SELECT * FROM developer_log ORDER BY timestamp DESC;";
$result = mysql_query($query);
oh_my_error(mysql_error(), $query);

while ($row = mysql_fetch_array($result)) {
	echo "<header>".date("Y/m/d", $row['timestamp'])."</header>";
	echo "<p><span class='miniwarning'>Category: ".$row['category']."</span><br />".$row['text']."</p>";
}

mysql_free_result($result);
//disconnect_sql($joke_connection);

?>
