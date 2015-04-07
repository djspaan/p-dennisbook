<?php
	require_once 'header.php';

	if (isset($_SESSION['user']))
	{
		destroySession();

		echo "<div class='main'>Je bent uitgelogd. Klik " . 
			"<a href='index.php'>hier</a> om de pagina te verversen.";
	}
	else echo "<div class='main'><br>" .
			"Je kan niet uitloggen want je bent niet ingelogd.";
?>

	<br><br></div>
</body>
</html>