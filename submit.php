<?php
		/*make sure results have been set*/
	if(isset($_GET["results"])){
			/*if results > 10, something when wrong*/
		if(intval($_GET["results"]) > 10){
			die("cheating!");
		}
			/*calculate accuracy*/
		$accuracy = intval($_GET["results"])/10;
		$mysqli = new mysqli("localhost", /*username*/ , /*password*/, "stochatta");
		$query = "INSERT INTO results VALUES(" . $accuracy . ")";
			
		$mysqli->query($query);
		if($mysqli->errno){
			echo $mysqli->error;
			exit(1);
		}
			/*update average total*/
		$query = "UPDATE average SET total = total + " . $accuracy . ", attempts = attempts + 1";
		$mysqli->query($query);
		if($mysqli->errno){
			echo $mysqli->error;
			exit(1);
		}
			/*redirect to results page*/
		header("Location: http://alissa.ninja/stochatta/results.php?results=" . $_GET["results"]);
	}else{
		echo "Results must be set and must be a number";
	}

?>
