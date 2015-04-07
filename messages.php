<?php
	require_once 'header.php';

	if (!$loggedin) die("<div class='container'><span class='alert alert-danger'>&#8658;" .
	"Je moet ingelogd zijn om deze pagina te kunnen zien.</span></div>");

	if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
	else $view = $user;

	if (isset($_POST['text']))
	{
		$text = sanitizeString($_POST['text']);

		if ($text != "")
		{
			$pm = substr(sanitizeString($_POST['pm']), 0, 1);
			$time = time();
			queryMysql("INSERT INTO messages VALUES(NULL, '$user', 
				'$view', '$pm', $time, '$text')");
		}
	}

	if ($view != "")
	{
		if ($view == $user) $name1 = $name2 = "Jouw";
		else
		{
			$name1 = "<a href='members.php?view=$view'>$view</a>'s";
			$name2 = "$view's";
		}
	

		echo "<div><h3>$name1 Berichten</h3></div>";
		
		if (isset($_GET['erase'])) 
		{
			$erase = sanitizeString($_GET['erase']);
			queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
		}
	
		$query = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
		$result = queryMysql($query);
		$num = $result->num_rows;

		echo "<div class='container-fluid well'>";
	
		for ($j = 0; $j < $num; ++$j)
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
	
			if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user)
			{
				echo date('M jS \'y g:ia', $row['time']);
	
				echo " <a href='messages.php?view=" . $row['auth'] . "'>" .
					$row['auth'] . "</a> ";
	
				if ($row['pm'] == 0)
					echo "schreef: &quot;" . $row['message'] . "&quot; ";
				else
					echo "<i>prive</i>: <span class='whisper'>&quot;" .
					$row['message'] . "&quot;</span> ";
	
				if ($row['recip'] == $user)
					echo "[<a href='messages.php?view=$view" .
					"&erase=" . $row['id'] . "'>verwijderen</a>]";
	
				echo "<br>";
			}
		}
	}

	if (!$num) echo "<br><span class='info'>Nog geen berichten.</span><br><br>";

	echo "<br><a class='btn btn-primary' href='messages.php?view=$view'>Ververs berichten</a></div>";

		echo <<<_END
			<div class='container-fluid well'>
				<form method='post' role='form' action='messages.php?view=$view'>
					<div class='form-group'>
						<label for='profile-text'>Typ hier om een bericht achter te laten:</label>
						<textarea name='text' class='form-control' cols='40' row='3'></textarea>
					</div>
					<div class='form-group'>
						<label><input type='radio' checked='true' name='pm' value='1'> Prive </label>
						<label><input type='radio' name='pm' value='0'> Openbaar </label>
					</div>
					<input type='submit' class='btn btn-primary' value='Stuur Bericht'>
				</form>
			</div>
_END;



?>

	</div><br>
</body>
</html>