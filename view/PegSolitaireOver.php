<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
                <title>Games - Peg Solitaire</title>
        </head>
        <body>
                <header><h1>Peg Solitaire: Congratulations!</h1></header>
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
				$numMoves = $_SESSION['PegSolitaire']->getNumMoves();
				$mess = $_SESSION['PegSolitaire']->getState();
				echo("<p>You were able to clear ".$numMoves."  pegs. "
					.(32-$numMoves)." pegs remain.</p>");
				echo("<p><center>".$mess."</center></p>");
			?>
			<br>
                        <form method="post">
				<center>
				<table>
					<?php	
						$board = $_SESSION["PegSolitaire"]->getBoard();
						for($i=0; $i<7; $i++){
							echo("<tr>");
							for($j=0; $j<7; $j++){
								echo("<td>");
								echo("<img src=peg".$board[$i][$j].".png name=i0 style='width:50px;height;50px'></button></td>");
							}
							echo("</tr>");
						}
					?>
				</table>
			
				</center> <br>
				<button type='submit' name='newgame' value='a'>New Game</button>
			</form>
		</main>
		<?php echo(view_errors($errors)); ?>
	</body>
</html> 
