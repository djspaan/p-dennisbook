<?php
	
	require_once 'functions.php';

	$userArray = Array();
	$returnString = "";

	$result = queryMysql("SELECT * FROM members");

	for ($i=0; $i < $result->num_rows; ++$i) 
	{ 
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userArray[] = $row['user'];
	}

	if (isset($_GET['q'])) 
	{
		$q = sanitizeString($_GET['q']);
		$result = queryMysql("SELECT * FROM members");

		foreach ($userArray as $username) 
		{
			if (preg_match("/$q/", strtolower($username))) 
			{
				$returnString = $returnString . "<a href='members.php?view=$username'>$username"
				 . "</a><br>";
			}
		}

	echo $returnString;

	}

?>