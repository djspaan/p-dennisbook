<!DOCTYPE html>
<html>
	<head>
		<title>dennisbook</title>
	</head>
	<body>
<?php
	require_once 'header.php';

	echo "<div class='jumbotron'><h1>Welkom op $appname!</h1>";

	if ($loggedin) echo "<p>$user, je bent ingelogd.</p></div>";
	else echo "<p>log in of registreer je om mee te doen!" .
		" Of: <a href='login.php?user=guest'>login als gast</a></p></div>";

?>
		</span>

	</body>
</html>
