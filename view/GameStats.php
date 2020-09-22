<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games - Game Stats</title>
	</head>
	<body>
		<header><h1>Game Stats</h1></header>
		<nav>
			<ul>
			<li> <a href="?nav=GameStats">Game Stats</a>
			<li> <a href="?nav=GuessGame">Guess Game</a>
			<li> <a href="?nav=15Puzzle">15 Puzzle</a>
			<li> <a href="?nav=PegSolitaire">Peg Solitaire</a>
			<li> <a href="?nav=Mastermind">Mastermind</a>
			<li> <a href="?nav=UserProfile">User Profile</a>
			<li> <a href="?nav=login">Logout</a>
                        </ul>
		</nav>
		<main>
			<?php 
				$games=$_SESSION['gamestats'];
				for($i=0; $i<count($tables); $i++){
					echo("<h3>".substr($tables[$i],0,-5).":</h3>
						<ul>
						<li> Number of Games Played: ".$games[$i]['numplays']."
						<li> Number of Games Won: ".$games[$i]['numwins']."
						<li> Best Score: ".$games[$i]['bestscore']."
						</ul> ");
				}
			?>
		</main>
        </body>
</html>
