<?php
	$mysqli = new mysqli("localhost", /*username*/, /*password*/ , "stochatta");
	if($mysqli->connect_errno){
		echo "connection error";
	}

		/*Queue is used to find n-gram markov chains.*/
	$stochatta = new SplQueue();

		/*words is number of tokens, sentences is a multiplier*/
	if(isset($_GET["words"]) && isset($_GET["sentences"])){
			/*prevent anything higher than 625 tokens*/
		for($i = 0; $i < intval($_GET["sentences"]) % 26; $i++){
			for($j = 0; $j < intval($_GET["words"]) % 26; $j++){
				$out = traverseMarkov($stochatta, $mysqli);
					/*put spaces before words, no space before punctuation*/
				if(!preg_match("/,|\.|\!|\;|\:|\?|\`|\~|\&/", $out)){
					echo " ";
					echo $out;
				}
				else{
					echo $out;
				}
				
			}
			/*reset queue every sentence*/
			$stochatta = new SplQueue();
		} 
	}
	
	function traverseMarkov($stochatta, $mysqli){
			/*third second and first contain a token as well as weighted incidence*/
		$third = array(0, "");
		$second = array(0, "");
		$first = array(0, "");
			/*require 3 tokens before we can build tri-gram*/
		if($stochatta->count() >= 3){
				/*search for tri-gram using weighted random -log(rand())/likelihood desc*/
			$query = "SELECT * FROM tokens WHERE third = '" . $stochatta->offsetGet(0) . "' AND second = '" . $stochatta->offsetGet(1) . "' AND first = '" . $stochatta->offsetGet(2) . "' ORDER BY -log(rand())/likelihood desc limit 1";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
				/*check if chain exists*/
			if(count($row) != 0){
					/*tri-gram has weight of 1*/
				$third = array(log(intval($row["likelihood"])), $row["next"]);
			}
		}
			/*require 2 tokens before we can build bi-gram*/
		if($stochatta->count() >= 2){

			$query = "SELECT * FROM tokens WHERE second = '" . $stochatta->offsetGet(1) . "' AND first = '" . $stochatta->offsetGet(2) . "' ORDER BY -log(rand())/likelihood desc limit 1";
			//echo '<div id="small">' . $query . "</div>";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			if(count($row) != 0){
					/*bi-gram has weight of 1.5*/
				$second = array(log(intval($row["likelihood"]))/1.5, $row["next"]);
			}
		}
			/*require 1 token before building uni-gram*/
		if($stochatta->count() >= 1){
			$query = "SELECT * FROM tokens WHERE first = '" . $stochatta->offsetGet(2) . "' ORDER BY -log(rand())/likelihood desc limit 1;";
			//echo '<div id="small">' . $query . "</div>";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			if(count($row) != 0){
					/*unigram has weight of 2*/
				$first = array(log(intval($row["likelihood"]))/2, $row["next"]);
			}
		}
			/*get random trigram*/
		if($stochatta->count() == 0){
			$query = "SELECT * FROM tokens WHERE third IS NOT NULL AND second IS NOT NULL AND first IS NOT NULL ORDER BY -log(rand())/likelihood LIMIT 1";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			if(count($row) != 0){
				$stochatta->enqueue($row["third"]);
				$stochatta->enqueue($row["second"]);
				$stochatta->enqueue($row["first"]);

					/*concatenate tokens*/
				$first3 = $row["third"];
				if(!preg_match("/\,|\.|\!|\;|\:|\?|\`|\~|\&/", $row["second"]))
					$first3 .= " ";
				$first3 .= $row["second"];
				if(!preg_match("/\,|\.|\!|\;|\:|\?|\`|\~|\&/", $row["first"]))
					$first3 .= " ";
				$first3 .= $row["first"];
			
				return $first3;
			}
		}
		
			/*compare weighted incidence and echo highest value*/
		if($first[0] > $second[0]){
			if($first[0] > $third[0]){
				$stochatta->enqueue($first[1]);
				if($stochatta->count() > 3){
					$stochatta->dequeue();
				}
				return $first[1];
			}else{
				$stochatta->enqueue($third[1]);
				if($stochatta->count() > 3){
					$stochatta->dequeue();
				}
				return $third[1];
			}
		}else{
			if($second[0] > $third[0]){
				$stochatta->enqueue($second[1]);
				if($stochatta->count() > 3){
					$stochatta->dequeue();
				}
				return $second[1];
			}else{
				$stochatta->enqueue($third[1]);
				if($stochatta->count() > 3){
					$stochatta->dequeue();
				}
				return $third[1];
			}
		}

	}
?>
