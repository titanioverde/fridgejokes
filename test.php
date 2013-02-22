<?php
error_reporting(E_ALL);
include "lib/common_sql.php";
$con = connect_sql();


?>



<body>
<?php
$vari = "0";
if (!$vari) {
	echo "My! I'm false";
}

 function SendMail($ToName, $ToEmail, $FromName, $FromEmail, $Subject, $Body, $Header)
{
$SMTP = fsockopen("smtp.gmail.com", 587);

$InputBuffer = fgets($SMTP, 1024);

fputs($SMTP, "HELO sitename.com\n");
$InputBuffer = fgets($SMTP, 1024);
fputs($SMTP, "MAIL From: $FromEmail\n");
$InputBuffer = fgets($SMTP, 1024);
fputs($SMTP, "RCPT To: $ToEmail\n");
$InputBuffer = fgets($SMTP, 1024);
fputs($SMTP, "DATA\n");
$InputBuffer = fgets($SMTP, 1024);
fputs($SMTP, "$Header");
fputs($SMTP, "From: $FromName <$FromEmail>\n");
fputs($SMTP, "To: $ToName <$ToEmail>\n");
fputs($SMTP, "Subject: $Subject\n\n");
fputs($SMTP, "$Body\r\n.\r\n");
fputs($SMTP, "QUIT\n");
$InputBuffer = fgets($SMTP, 1024);
echo $InputBuffer;

fclose($SMTP);
}

//SendMail("Titanio", "titanioverde@gmail.com", "Verde", "titanioverde@gmail.com", "HALLO", "EVERYNYAN", "");
echo mail("titanioverde@gmail.com", "HAWAA YU", "FAIN SANKYU");
?>

&#x266B;

<form method='post' action='test2.php'>
	<input type='checkbox' name='checkme' /> Check my check!
	<input type='submit'>
</form>

<div id='test'><p>Nothing.</p></div>

<script>/*
	$.ajax("test_json.php", {
		dataType: "json",
		success: function(data) {
			$("div#test p").html(data.submitter)
		}
	});*/
</script>	
</body>