<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
 	$_REQUEST['tile']=!empty($_REQUEST['tile']) ? $_REQUEST['tile'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="uft-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Games - 15 Puzzle</title>
	</head>
	<body>
                <header><h1>15 Puzzle</h1></header>
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
			<form action="index.php" method="post">
				<center>
				<table>	
					<?php
						$array=$_SESSION["15Puzzle"]->getarray();
						$k=0;
						for($i=0;$i<16;$i++){
							if($i==0||$i==4||$i==8||$i==12){ ?>
								<tr>
							<?php }
							$k++ ?>
							<td>
							<button type='submit' name="tile" value=  <?php echo $array[$i] ?> ><img src= <?php echo $array[$i].".jpg" ?> name=i0>
							</td>
							<?php if($i==3||$i==7||$i==11||$i==15){ ?>                                                                                                            
								</tr>
							<?php } 
		 
						}
					?>
				</table>
				</center> <br>
				<button type='submit' name='newgame' value='a'>New Game</button>
			</form>
		</main>
	</body>
</html>
