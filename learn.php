<?php

	/*open file; kant.html in this case*/
$rawSource = file_get_contents("kant.html");

$mysqli = new mysqli("localhost", /*username*/ , /*password*/ , "stochatta");

$sourceMaterial = new DOMDocument();
$sourceMaterial->loadHTML($rawSource);
	/*using dom methods to choose only content in file*/
$xPath = new DomXPath($sourceMaterial);

	/*used to create markov chains*/
$stochatta = new SplQueue();

	/*count the number of queries so far*/
$i = 3;

$query = "INSERT INTO tokens VALUES ";

	/*for every <p> element, learn*/
foreach($xPath->query("//p") as $paragraph){
	foreach($paragraph->childNodes as $node){
			//remove parentheses from source material since they are syntactically irrelevant
		$reduce = preg_replace("/\(.+\)/", "", $node->nodeValue);
			//match words and valid punctuation
		preg_match_all("/[A-Za-z-]+|\,|\.|\!|\;|\:|\?|\`|\~|\&/", $reduce, $terms);
			/*create markov chain with terms*/
		for($j = 0; $j < count($terms[0]); $j++){
			$stochatta->enqueue($terms[0][$j]);
			
				/*ensure markov chain only stays at 4 member (3 preceding states plus the next state)*/
			if($stochatta->count() > 4){
				$stochatta->dequeue();
			}
				/*at 4 members, insert the tri-, bi-, and uni-gram markov chain into the database*/
			if($stochatta->count() == 4){
				$query .= "\n(\"" . $stochatta->offsetGet(0) . "\", \"" . $stochatta->offsetGet(1) . "\", \"" . $stochatta->offsetGet(2) . "\", \"" . $stochatta->offsetGet(3) . "\", 1),\n";
				$query .= "(\"\", \"" . $stochatta->offsetGet(1) . "\", \"" . $stochatta->offsetGet(2) . "\", \"" . $stochatta->offsetGet(3) . "\", 1),\n";
				$query .= "(\"\", \"\", \"" . $stochatta->offsetGet(2) . "\", \"" . $stochatta->offsetGet(3) . "\", 1),";
			}
		}
			/*add 3 queries*/
		$i+=3;
		
			/*at 300 queries, submit query to database and start over*/
		if($i % 300  == 0){
			$query = substr($query, 0, -1) . " ON DUPLICATE KEY UPDATE tokens.likelihood=tokens.likelihood+1";

			$mysqli->query($query);
			if($mysqli->error){
				echo $mysqli->error;
				exit(-1);
			}
			echo "queried\n";
			$query = "INSERT INTO tokens VALUES ";
		}

	}
}
echo "the end";
?>
