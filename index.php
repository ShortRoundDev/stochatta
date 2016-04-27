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
	<img id="logo" src="logo.png" alt="stochatta">
<div align="center">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- top-banner -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-7669564802643542"
     data-ad-slot="9082142715"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

	</div>
	<div id="content">
		<div id="title">Here's a sentence:</div>
		<div class="selection" id="contentarea">Loading...</div>
	</div>

	<div id="copyright">
		Icons by <a href="http://www.flaticon.com/authors/freepik">Freepik</a> and <a href="http://www.flaticon.com/authors/epiccoders">EpicCoders</a> on <a href="http://flaticon.com/">FlatIcon</a>
	</div>

	<script>
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("contentarea").innerHTML = "<i>[...] " + xhr.responseText + " [...]</i>";
			}else if(xhr.status != 0){
				document.getElementById("contentarea").innerHTML = document.getElementById("contentarea").innerHTML + "\n" + xhr.readyState + ", " + xhr.status + ": " + xhr.statusText;
			}
		}
		xhr.open("GET", "markov.php?words=25&sentences=2");
		xhr.send();


	</script>
	

</body>
</html>
