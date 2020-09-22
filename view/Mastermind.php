<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="uft-8">
                <link rel="stylesheet" type="text/css" href="style.css">
                <title>Games - Mastermind</title>
        </head>
        <body>
                <header><h1>Mastermind</h1></header>
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
		<button type="button" onclick="alert('Try to guess the secret colour order! After each round there will be feedback. A grey square means you picked a right colour in the wrong position. A black square means you picked the right colour in the right position. Have fun!')">Instructions</button>
		<br> <br>
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
			for($i=0;$i<10;$i++){ ?>
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
			<input type="submit" style="background-color:blue;" name="mmclick" value="blue">
			<input type="submit" style="background-color:green;" name="mmclick" value="green"> 
			<input type="submit" style="background-color:red;" name="mmclick" value="red"> 
			<input type="submit" style="background-color:brown;" name="mmclick" value="brown"> 
			<input type="submit" style="background-color:purple;" name="mmclick" value="purple"> 
			<input type="submit" style="background-color:orange;" name="mmclick" value="orange"> 
			<br>
			<input type="submit" style="background-color:white;" name="control" value="getfeedback">
			<input type="submit" style="background-color:white;" name="control" value="delete">
			<br> <br>
			<input type="submit" style="background-color:white;" name="control" value="Start again">
		</form>
		</center>
		</main>
	</body>
</html>
