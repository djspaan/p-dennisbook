<?php
	require_once 'header.php';

	if (!$loggedin) die("<div class='container'><span class='alert alert-danger'>&#8658;" .
	"Je moet ingelogd zijn om deze pagina te kunnen zien.</span></div>");

	echo "<h3>Jouw Profiel</h3>";

	$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

	if (isset($_POST['text'])) {
		$text = sanitizeString($_POST['text']);
		$text = preg_replace('/\s\s+/', ' ', $text);

		if ($result->num_rows)
			queryMysql("UPDATE profiles SET text='$text' WHERE user='$user'");
		else queryMysql("INSERT INTO profiles VALUES('$user', '$text')");
	}
	else {
		if ($result->num_rows) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$text = stripslashes($row['text']);
		}
		else $text = "";
	}

	$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

	if (isset($_FILES['image']['name'])) {
		$saveto = "$user.jpg";
		move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
		$typeok = TRUE;

		switch($_FILES['image']['type']) {
			case "image/gif":	$src = imagecreatefromgif($saveto); break;
			case "image/jpeg":
			case "image/pjpeg":	$src = imagecreatefromjpeg($saveto); break;
			case "image/png":	$src = imagecreatefrompng($saveto); break;
			default:			$typeok = FALSE; break;
		}

		if ($typeok) {
			list($w, $h) = getimagesize($saveto);

			$max = 100;
			$tw = $w;
			$th = $h;

			if ($w > $h && $max < $w) {
				$th = $max / $w * $h;
				$tw = $max;
			}
			elseif ($h > $w && $max < $h) {
				$tw = $max / $h * $w;
				$th = $max;
			}
			elseif ($max < $w) {
				$tw = $th = $max;
			}

			$tmp = imagecreatetruecolor($tw, $th);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
			imageconvolution($tmp, array(array(-1, -1, -1),
			array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
			imagejpeg($tmp, $saveto);
			imagedestroy($tmp);
			imagedestroy($src);
		}
	}


	showProfile($user);


	echo <<<_END
		<h3>Profiel bewerken</h3>
		<div class='container-fluid well'>
			<div class='form-group container-fluid well'>
			<form method='post' action='profile.php' enctype='multipart/form-data'>
				<div class='row'>
					<label for='text'>Bewerk je profieltekst of upload een profielfoto</label>
					<textarea class='form-control' name='text' rows='5'>$text</textarea>
				</div>
				<div class='row'>
					<br><label for='image'>Upload een profielfoto</label><br>
					<input class='btn btn-default btn-file' type="file" name='image' class="file btn">

					<br><br><input type='submit' value='Profiel opslaan' class='btn btn-primary btn-block'>
				</div>
			</form>
		</div>
_END;


?>
</body>
</html>
