<?php
	require_once 'header.php';

	if (!$loggedin) die("<div class='container'><span class='alert alert-danger'>&#8658;" .
	"Je moet ingelogd zijn om deze pagina te kunnen zien.</span></div>");

	echo "<div>";

	if (isset($_GET['view'])) {
		$view = sanitizeString($_GET['view']);

		if ($view == $user) $name = "Jouw";
		else 				$name = $view . "s";

		echo "<h3>$name Profiel</h3>";
		showProfile($view);

		echo "<a class='btn btn-primary' href='messages.php?view=$view'>" .
			"Bekijk $name berichten</a>";
		die ("</div></body></html>");

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
		die("<div></body></html>");
	}

	if (isset($_GET['add'])) {
		$add = sanitizeString($_GET['add']);

		$result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");

		if (!$result->num_rows)
			queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
	}
	elseif (isset($_GET['remove'])) {
		$remove = sanitizeString($_GET['remove']);
		queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
	}

	$result = queryMysql("SELECT user FROM members ORDER BY user");
	$num = $result->num_rows;

	echo <<<_END
		<h3>Andere leden</h3>
		<div class='container-fluid well'>
			<table class='table table-hover'>
				<thead>
					<tr>
						<th>Naam</th>
						<th>Relatie</th>
					</tr>
				</thead>
				<tbody>
_END;

	for ($j = 0; $j < $num; ++$j)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if ($row['user'] == $user) continue;

		echo "<tr><td><a href='members.php?view=" . $row['user'] .
			"'>" . $row['user'] . "</a></td>";

		$follow = "volgen";

		$result1 = queryMysql("SELECT * FROM friends WHERE user='" .
			$row['user'] . "' AND friend = '$user'");
		$t1 = $result1->num_rows;
		$result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" .
			$row['user'] . "'");
		$t2 = $result1->num_rows;

		echo "<td>";

		if (($t1 + $t2) > 1)	echo " &harr; volgen elkaar";
		elseif ($t1)			echo "&larr; volg jij";
		elseif ($t2) {
			echo "&rarr; volgt jou";
			$follow = "terugvolgen";
		}

		if (!$t1) echo " [<a href='members.php?add=" . $row['user'] .
			"'>$follow</a>]";
		else echo " [<a href='members.php?remove=" . $row['user'] . "'>niet meer volgen</a>]";

		echo "</td></tr>";
	}

	echo "</tbody></table></div>";
?>

	</ul></div>
</body>
</html>
