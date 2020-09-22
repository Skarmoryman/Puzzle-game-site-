<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="uft-8">
                <link rel="stylesheet" type="text/css" href="style.css">
                <title>Games - Mastermind</title>
        </head>
        <body>
                <header><h1>Mastermind: You Win!</h1></header>
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

		<style>
        		.dot{
		                height:25px;
                		width: 25px;
		                display:inline-block;
        		}
		</style>
		<center>
		<p>Congratulations!</p>
		<table border="2">
		<tr>
			<th>Round</th>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>Feedback</th>
		</tr>
		<?php	
			$gamearray=$_SESSION["Mastermind"]->getgamearray();
			$feedbackarray=$_SESSION["Mastermind"]->getfeedbackarray();
			for($i=0;$i<10;$i++){?>
		        <tr>
			        <th> <?php echo $i+1; ?> </th>
				<?php for($j=0;$j<4;$j++){ ?>
					<th> <span class="dot" style= <?php echo "background-color:".$gamearray[$i][$j]; ?> ></span> </th>
				<?php  } ?>
				<th>  <span class="dot" style= <?php echo "background-color:".$feedbackarray[$i][0]; ?> ></span>
			        <span class="dot" style= <?php echo "background-color:".$feedbackarray[$i][1]; ?> ></span>
			        <span class="dot" style= <?php echo "background-color:".$feedbackarray[$i][2]; ?> ></span>
			        <span class="dot" style= <?php echo "background-color:".$feedbackarray[$i][3]; ?> ></span>
				</th>
		        </tr>
			<?php } ?>
		
		</table>
		<br>
		<form action="index.php" method="post">
			<input type="submit" style="background-color:white;" name="control" value="Start again">
		</form>
		</center>
		</main>
	</body>
</html>
