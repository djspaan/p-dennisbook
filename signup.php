<?php
	require_once 'header.php';

	echo <<<_END
		<script>
			function checkUser(user)
			{
				if (user.value == ''){
					O('info').innerHTML = ''
					return
				}

				params = "user=" + user.value
				request = new ajaxRequest()
				request.open("POST", "checkuser.php", true)
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
				request.setRequestHeader("Content-length", params.length)
				request.setRequestHeader("Connection", "close")

				request.onreadystatechange = function()
				{
					if (this.readyState == 4)
						if (this.status == 200)
							if (this.responseText != null)
								O('info').innerHTML = this.responseText
				}

				request.send(params)
			}

			function ajaxRequest()
			{
				try { var request = new XMLHttpRequest() }
				catch(e1) {
					try { request = new ActiveXObject("Msxml2.XMLHTTP") }
					catch(e2) {
						try { request = new ActiveXObject("Microsoft.XMLHTTP") }
						catch(e3) {
							request = false
						}
					}
				}

				return request
			}
		</script>
		<div class='main'><h3>Voer je gegevens in om je te registreren</h3>
_END;

	$error = $user = $pass = "";

	if (isset($_SESSION['user'])) destroySession();

	if (isset($_POST['user'])){
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
		$token = hash('ripemd128', "$salt1$pass$salt2");

		if ($user == "" || $pass = "")
			$error = "Niet alle velden zijn ingevuld<br><br>";
		else {
			$result = queryMysql("SELECT * FROM members WHERE user='$user'");

			if ($result->num_rows)
				$error = "Die gebruikersnaam bestaat al<br><br>";
			else {
				queryMysql("INSERT INTO members VALUES('$user', '$token')");
				die("<div class='container-fluid well'><h4>Account aangemaakt</h4>Log <a href='login.php'>hier</a> in.</div>");
			}
		}
	}

	echo <<<_END
<div class='container-fluid well'>
	<form method='post' action='signup.php' class='form-horizontal' role='form'>
		<div class='form-group'>$error
			<label class="control-label col-sm-2" for="user">Gebruikersnaam:</label>
			<div class='col-sm-6'>
				<input type='text' class='form-control' maxlength='16' name='user' onBlur='checkUser(this)' value='$user'>
			</div>
			<div class='col-sm-4' id='info'>
			</div>
		</div>
		<div class='form-group'>
			<label class="control-label col-sm-2" for="pwd">Wachtwoord:</label>
			<div class='col-sm-6'>
				<input type='password' class='form-control' maxlength='16' name='pass' value='$pass'>
			</div>
		</div>
	    <div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  <button type="submit" class="btn btn-primary" value='signup'>Registreren</button>
	    	</div>
	  	</div>
	</form>
</div>
</body>
</html> 
_END;
?>
