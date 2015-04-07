<?php
	$dbhost = 'DBHOST';
	$dbname = 'DBNAME';
	$dbuser = 'DBUSERNAME'
	$dbpass = 'DBPASSWORD';
	$appname = "dennisbook";
	$salt1 = "SALTHERE";
	$salt2 = "SALTHERE";

	$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($connection->connect_error) die($connection->connect_error);

	function createTable($name, $query)
	{
		queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
		echo "Table $name created or already exists.<br>";
	}

	function queryMysql($query)
	{
		global $connection;

		$result = $connection->query($query);
		if (!$result) die($connection->error);

		return $result;
	}

	function destroySession()
	{
		$_SESSION = array();

		if (session_id() != "" || isset($_COOKIE[session_name()]))
			setcookie(session_name(), '', time() - 259200, '/');

		session_destroy();
	}

	function sanitizeString($var)
	{
		global $connection;

		$var = strip_tags($var);
		$var = htmlentities($var);
		$var = stripslashes($var);
		return $connection->real_escape_string($var);
	}

	function showProfile($user)
	{
		echo "<div class='container-fluid well'>";
		if (file_exists("$user.jpg"))
			echo "<div class='well container-fluid'><img src='$user.jpg'>";
		else
			echo "<div class='well container-fluid'><img src='Gast.jpg'>";

		$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

		if ($result->num_rows)
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo "<div class='well'>";
			echo stripslashes($row['text']);
			echo "</div></div></div>";
		}
		else {
			echo "<div class='well'>";
			echo "Deze gebruiker heeft nog geen profieltekst ingesteld.";
			echo "</div></div></div>";
		}
	}
?>
