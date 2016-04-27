<?php
	if(isset($_GET["results"])){
		if(intval($_GET["results"]) > 9){
			die("cheating!");
		}
		$accuracy = intval($_GET["results"])/10;
		$mysqli = new mysqli("localhost", /*username*/ , /*password*/, "stochatta");
		$query = "INSERT INTO results VALUES(" . $accuracy . ")";
		$mysqli->query($query);
		if($mysqli->errno){
			echo $mysqli->error;
			exit(1);
		}
	
		$query = "UPDATE average SET total = total + " . $accuracy . ", attempts = attempts + 1";
		$mysqli->query($query);
		if($mysqli->errno){
			echo $mysqli->error;
			exit(1);
		}

		header("Location: http://alissa.ninja/stochatta/results.php?results=" . $_GET["results"]);
	}else{
		echo "Results must be set and must be a number";
	}

?>
