<?php
	require_once 'functions.php';

	if (isset($_POST['user'])){
		$user = sanitizeString($_POST['user']);
		$result = queryMysql("SELECT * FROM members WHERE user='$user'");

		if ($result->num_rows)
			echo "<div class='alert alert-danger'>" .
			"Deze gebruikersnaam bestaat al</div>";
		else
			echo "<div class='alert alert-success'>" .
			"Deze gebruikersnaam is beschikbaar!</div>";
	}
?>