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
		<title>Games - Registration</title>
	</head>
	<body>
		<header><h1>Registration</h1></header>
		<main>
			<h1>Sign up</h1>
			<form action="index.php" method="post">
				<table>
					<!-- Trick below to re-fill the user form field -->
					<tr><th><label for="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
					<tr><th><label for="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
					<tr><th><label for="password">Password confirmation</label></th><td> <input type="password" name="passwordconf" /></td></tr>
					<tr><th> 
					Select age: <select name="age">
					<option value="10">10-</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
                                        <option value="14">14</option>
					<option value="15">15</option>
                                        <option value="16">16</option>
					<option value="17">17</option>
                                        <option value="18">18+</option>
					</select>
					<br/> <br/>
					</tr></th>	
					<th>	
						<p>Skill Level</p>
						Low<br>
						<input type="radio" name="skill" value="low"><br>
						Medium<br>
						<input type="radio" name="skill" value="medium"><br>
						High<br>
                                       		<input type="radio" name="skill" value="high"><br>
					</th>
					<?php if($_SESSION['registered']==true){echo "successfully made profile";} ?>	
					<tr><th><br>&nbsp;<input type="submit" name="submit" value="register" /></tr></th>
					<tr><th>
					
                                        <br>Terms of Services:<br>do you agree to have fun? <br/>
                        <input name="item" type="checkbox" value="yes"> yes <br/>
                        <br/>
					</tr></th>
					
					<tr><th> 
					<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					<tr><th>&nbsp;</th><td><?php echo(view_errors($recommendation)); ?></td></tr>
					
				</table>
			</form>
			<a href="?nav=login">Log In</a>
		</main>
		<footer>
		</footer>
	</body>
</html>
