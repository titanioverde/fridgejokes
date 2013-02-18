<html>
<head><title>Fridge Jokes</title>

<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	$joke_connection = connect_sql();
}

?>

<link rel='stylesheet' href='<?php echo $css_file ?>'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
<script src='./prefixfree.min.js'></script>
<script src='./lib/view.js'></script>


</head>

<body>




<div id='joke_text' class='simple'></div>
<?php echo "<p><a href='".$main_page."'>Read another joke? You should come to the full version of P4 Fridge Jokes.</a></p>"; ?>

<script>
    $(document).ready(function() {
        simple_read_joke(<?php
                        if ($_GET['joke'] > 0) echo "{ joke: ".$_GET['joke']."}"; ?>);
    });
</script>
</body>