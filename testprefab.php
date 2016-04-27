<?php

	$mysqli = new mysqli("localhost", /*username*/ , /*password*/ , "stochatta");

	$result = $mysqli->query("SELECT * FROM prefabs ORDER BY rand() LIMIT 1");
	$row = $result->fetch_assoc();
	echo $row["text"];

?>
