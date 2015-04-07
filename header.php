<?php
	session_start();

	require_once 'functions.php';

	$userstr = ' (Guest)';

	if (isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		$loggedin = TRUE;
		$userstr = " ($user)";
	}
	else $loggedin = FALSE;

	echo <<<_END
	<!DOCTYPE html>
	<html>
		<head>
			<title>$appname$userstr</title>
			<link rel='stylesheet' href='style.css' type='text/css'>
			<link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
			<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
			<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
		</head>
		<body>
			<div class='container'>
				<center><canvas id='logo' width='590' height='100'>$appname</canvas></center>
				<script src='javascript.js'></script>
_END;
	if ($loggedin)
		//  navbar when logged in
		echo <<<_END
<nav class='navbar navbar-inverse'>
	<div class='container-fluid'>
		<div>
			<ul class='nav navbar-nav'>
				<li><a href='members.php?view=$user'>Home</a></li>
				<li><a href='members.php'>Leden</a></li>
				<li><a href='friends.php'>Vrienden</a></li>
				<li><a href='messages.php'>Berichten</a></li>
				<li><a href='profile.php'>Profiel</a></li>
			</ul>
			<ul class='nav navbar-nav navbar-right inline'>
				<li><form>
					<label for='livesearch'>Zoek gebruikers:
					<input type="text" class='form-control' size='40' onkeyup="showResult(this.value)"></label>
					<div id="livesearch" style='background:white;'></div>
				</form></li>
				<li><a href='logout.php'><span class="glyphicon glyphicon-log-out"></span> Log uit</a></li>
			</ul>
		</div>
	</div>
</nav>
_END;
	else
		// navbar when logged out
		echo <<<_END
<nav class='navbar navbar-inverse'>
	<div class='container-fluid'>
		<div>
			<ul class='nav navbar-nav'>
				<li><a href='index.php'>Home</a></li>
			</ul>
			<ul class='nav navbar-nav navbar-right'>
				<li><a href='login.php'><span class="glyphicon glyphicon-log-in"></span> Inloggen</a></li>
				<li><a href='signup.php'><span class="glyphicon glyphicon-user"></span> Registreren</a></li>
			</ul>
		</div>
	</div>
</nav>
_END;
?>
