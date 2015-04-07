<?php
	require_once 'header.php';

	echo "<div class='main'><h3>Voer je gegevens in om in te loggen</h3>";

	$error = $user = $pass = $token = "";

	if (isset($_GET['user'])) {
		$_POST['user'] = "Gast";
		$_POST['pass'] = "gast8";
	}

	if (isset($_POST['user'])) {
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
		$token = hash('ripemd128', "$salt1$pass$salt2");

		if ($user == "" || $pass == "")
			$error = "Niet alle velden zijn ingevuld<br>";
		else {
			$result = queryMysql("SELECT user,pass FROM members
				WHERE user='$user' AND pass='$token'");

			if ($result->num_rows == 0)
				$error = "<span class='error'>Gebruikersnaam/wachtwoord incorrect</span><br><br>";
			else {
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $token;
				die("Je bent nu ingelogd. Klik <a href='members.php?view=$user'>" .
					"hier</a> om verder te gaan.<br><br>");
			}
		}
	}

	echo <<<_END
<div class='container-fluid well'>
	<form method='post' action='login.php' class='form-horizontal' role='form'>
		<div class='form-group'>$error
			<label class="control-label col-sm-2" for="user">Gebruikersnaam:</label>
			<div class='col-sm-10'>
				<input type='text' class='form-control' maxlength='16' name='user' value='$user'>
			</div>
		</div>
		<div class='form-group'>
			<label class="control-label col-sm-2" for="pwd">Wachtwoord:</label>
			<div class='col-sm-10'>
				<input type='password' class='form-control' maxlength='16' name='pass' value='$pass'>
			</div>
		</div>
	  	<div class="form-group">
	  	  <div class="col-sm-offset-2 col-sm-10">
	  	    <div class="checkbox">
	  	      <label><input type="checkbox"> Onthoud mij</label>
	  	    </div>
	  	  </div>
	  	</div>
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  <button type="submit" class="btn btn-primary" value='login'>Log in</button>
	    	</div>
	  	</div>
	</form>
</div>
_END;
?>
</body>
</html>
