<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7

$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games - User Profile</title>
	</head>
	<body>
		<header><h1>User Profile</h1></header>
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
			<h1>Update Information</h1>
			<form action="index.php" method="post">
				<table>
					<tr><th><label for="password">New Password</label></th><td> <input type="password" name="password" /></td></tr>
					<tr><th><label for="password">New Password confirmation</label></th><td> <input type="password" name="passwordconf" /></td></tr>
					<th>	
						<p>skill level</p>
						low<br>
						<input type="radio" name="skill" value="low"><br>
						medium<br>
						<input type="radio" name="skill" value="medium"><br>
						high<br>
                                       		<input type="radio" name="skill" value="high"><br>
					</th>
					
					<tr><th>
			<br>
                        <br/>
                        </tr></th>

					<?php if($_SESSION['registered']==true){echo "successfully updated profile";} ?>	
					<tr><th><br>&nbsp;<input type="submit" name="submit" value="update" /></tr></th>
					<tr><th> 
					<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					
				</table>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>


