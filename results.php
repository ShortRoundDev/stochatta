<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" type="text/css" href="style.css">

	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>
<body>
	<div id="navbar">
		<a href="index.php">
		<div class="icon"><img src="res/buildings.png">
			<div class="tagline">Home</div>
		</div>
		</a>
		<a href="about.html">
		<div class="icon"><img src="res/sign.png">
			<div class="tagline">Info</div>
		</div>
		</a>
		<a href="test.php">
		<div class="icon"><img src="res/science.png">
			<div class="tagline">Testing</div>
		</div>
		</a>
		<a href="sources.html">
		<div class="icon"><img src="res/library.png">
			<div class="tagline">Sources</div>
		</div>
		</a>
	</div>
	<div id="textContent">
<?php

		/*display results*/
	if(isset($_GET["results"])){
		echo "<h1>You were <u style=\"color: red\">";
		echo intval((intval($_GET["results"])/10) * 100) . "%</u> correct, with " . $_GET["results"] . "/10 correct answers</h1>";
		$mysqli = new mysqli("localhost", /*username*/, /*password*/, "stochatta");

		$result = $mysqli->query("SELECT * FROM average");
		$row = $result->fetch_assoc();
		
		echo "<h1>This compares to the global average of <u style=\"color: red\">";
		echo intval(doubleval($row["total"])/intval($row["attempts"]) * 100) . "%";
		echo "</u></h1>";
	}else{
		echo "<h1>error</h1>";
	}

?>
	</h1>
	</div>
</body>
