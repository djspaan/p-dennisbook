<?php
	require_once 'header.php';

	if (!$loggedin) die("<div class='container'><span class='alert alert-danger'>&#8658;" .
	"Je moet ingelogd zijn om deze pagina te kunnen zien.</span></div>");

	if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
	else $view = $user;

	$followers = array();
	$following = array();

	$result = queryMysql("SELECT * FROM friends WHERE user='$view'");
	$num = $result->num_rows;

	for ($j = 0; $j < $num; ++$j) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$followers[$j] = $row['friend'];
	}

	$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
	$num = $result->num_rows;

	for ($j = 0; $j < $num; ++$j) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$following[$j] = $row['user'];
	}

	$mutual = array_intersect($followers, $following);
	$followers = array_diff($followers, $mutual);
	$following = array_diff($following, $mutual);
	$friends = FALSE;

	if (sizeof($mutual)) {
		echo "<h3>Wederzijds</h3>";
		echo "<div class='well container-fluid'>";
		foreach($mutual as $friend)

			if (file_exists("$friend.jpg")) 
			{
				echo "<div class='span-4 column'>";
				echo "<img src='$friend.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
			else
			{
				echo "<div class='span-4 column'>";
				echo "<img src='Gast.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
		echo "</div>";
		$friends = TRUE;
	}

	if (sizeof($followers)) {
		echo "<h3>Volgers</h3>";
		echo "<div class='well container-fluid'>";
		foreach($followers as $friend)
			if (file_exists("$friend.jpg")) 
			{
				echo "<div class='span-4 column'>";
				echo "<img src='$friend.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
			else
			{
				echo "<div class='span-4 column'>";
				echo "<img src='Gast.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
		echo "</div>";
		$friends = TRUE;
	}

	if (sizeof($following)) {
		echo "<h3>Volgend</h3>";
		echo "<div class='well container-fluid'>";
		foreach($following as $friend)
			if (file_exists("$friend.jpg")) 
			{
				echo "<div class='span-4 column'>";
				echo "<img src='$friend.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
			else
			{
				echo "<div class='span-4 column'>";
				echo "<img src='Gast.jpg'>";
				echo "<br><a href='members.php?view=$friend'>$friend</a>";
				echo "</div>";
			}
		echo "</div>";
		$friends = TRUE;
	}

	if (!$friends) echo "<br>Je hebt nog geen vrienden.<br><br>";
?>

	</div><br>
</body>
</html>