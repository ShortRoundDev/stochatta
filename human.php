<?php
		/*returns a random human quote*/
	$mysqli = new mysqli("localhost", /*username*/ , /*password*/ , "stochatta");
	$query = "SELECT * FROM humans ORDER BY rand()";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	echo $row["text"];

?>
