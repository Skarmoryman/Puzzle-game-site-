<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/15Puzzle.php";
	require_once "model/GuessGame.php";
	require_once "model/MasterModel.php";
	require_once "model/PegSolitaire.php";
	
	session_save_path("sess");
	session_start(); 
	$_SESSION['registered']=false;
	$dbconn = db_connect();

	$errors=array();
	$recommendation=array();
	$view="";
	$tables = array("GuessGameScore", "Puzzle15Score", "PegSolitaireScore", "MastermindScore");
	
	
	/* controller code */

	/* local actions, these are state transforms */
	if(!isset($_SESSION['state'])){
		$_SESSION['state']='login';
	}

	if((isset($_GET['nav']) && !empty($_SESSION['user'])) || isset($_GET['nav']) && ($_GET['nav']=="register"||$_GET['nav']=="login")){
		$_SESSION['state']=$_REQUEST['nav'];
		if($_SESSION['state']=='GameStats') {
			$_SESSION['gamestats']=array();
			for($i=0; $i<count($tables); $i++){
				$query = "SELECT numPlays, numWins, bestScore FROM ".$tables[$i]." WHERE userid=$1";
				$result = pg_prepare($dbconn, "", $query);
				$result = pg_execute($dbconn, "", array($_SESSION['user']));
				array_push($_SESSION['gamestats'], pg_fetch_array($result, NULL, PGSQL_ASSOC));
			}
		} else if ($_SESSION['state']=='login') {
			session_destroy();
			session_start();
			$_SESSION['state']='login';
		}
	}

	switch($_SESSION['state']){
	                
		case "login":
			// the view we display by default
			$view="login.php";
				
			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login"){
				break;
			}
			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}
			$query = "SELECT password FROM appuser WHERE userid=$1;";
                	$result = pg_prepare($dbconn, "", $query);
                	$result = pg_execute($dbconn, "", array($_REQUEST['user']));
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
                	if(password_verify($_REQUEST['password'], $row['password'])){
				$_SESSION['user']=$_REQUEST['user'];
				$_SESSION['state']='GameStats';
				$view="GameStats.php";	
				$_SESSION['gamestats']=array();
				for($i=0; $i<count($tables); $i++){
					$query = "SELECT numPlays, numWins, bestScore FROM ".$tables[$i]." WHERE userid=$1";
					$result = pg_prepare($dbconn, "", $query);
					$result = pg_execute($dbconn, "", array($_SESSION['user']));
					array_push($_SESSION['gamestats'], pg_fetch_array($result, NULL, PGSQL_ASSOC));
				}
			} else {
				$errors[]="invalid login";
			}
			break;


		case "register":
			$view="register.php";

			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="register"){
				break;
			}
			if(empty($_REQUEST['user']))$errors[]='user is required';
			else if(empty($_REQUEST['password']))$errors[]='password is required';
			else if(empty($_REQUEST['passwordconf']))$errors[]='password confirmation is required';
			else if(($_REQUEST['passwordconf'])!=$_REQUEST['password'])$errors[]='password and confirmation do not match';
			else if(empty($_REQUEST['age']))$errors[]='please tell us your age';
			else if($_REQUEST['age']<13)$errors[]='must be 13 years or older to register';
			else if(empty($_REQUEST['item']))$errors[]='please agree to terms and services';
			//no errors so start database
			$user = $_REQUEST['user'];
			$query  = "SELECT * FROM appuser WHERE userid=$1";
			$result = pg_prepare($dbconn, "", $query);
			$result = pg_execute($dbconn, "", array($user));
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
			if(!empty($row)) $errors[]='username already exists';
			if(!empty($errors))break;
			
			//Salts and Hashes password
			$hashword = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
			$query = "INSERT INTO APPUSER (USERID, PASSWORD) VALUES ($1, $2)";
			$result = pg_prepare($dbconn, "", $query);
			$result = pg_execute($dbconn, "", array($user, $hashword));
			$_SESSION['registered']=true;
			if(!empty($_REQUEST['skill'])){
				if($_REQUEST['skill']=="low"){
					$errors[]='We recommend Guess Game. Have fun!';		
				}else if($_REQUEST['skill']=="medium"){
					$errors[]='We recommend Solitaire. Have fun!';
				}else{
					$errors[]='We recommend 15 puzzle and Mastermind. Have fun!';
				}
			}
			break;	
		case "UserProfile":
			$view="UserProfile.php";

			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="update"){
				break;
			}
			if(empty($_REQUEST['password']))$errors[]='password is required';
			else if(empty($_REQUEST['passwordconf']))$errors[]='password confirmation is required';
			else if(($_REQUEST['passwordconf'])!=$_REQUEST['password'])$errors[]='password and confirmation do not match';
			if(!empty($errors))break;
			$hashword=password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
			$user=$_SESSION['user'];
			$query="UPDATE appuser SET password = '".$hashword."' WHERE userid=$1";
			$result = pg_prepare($dbconn, "", $query);
			$result = pg_execute($dbconn, "", array($user));
			$_SESSION['registered']=true;
			break;


		case "GameStats":
			$view="GameStats.php";
			break;

				
		case "GuessGame":
			// default view
			$view="GuessGame.php";
			// create new instance of game
			if(!isset($_SESSION['GuessGame'])) $_SESSION['GuessGame'] = new GuessGame($dbconn, $_SESSION['user']);
			
			if(!empty($_REQUEST['newgame'])){
				$_REQUEST['newgame'] = "";
				$_SESSION["GuessGame"]->newGame($dbconn, $_SESSION['user']);
				break;
			}
			// handle user move	
			if(!empty($_REQUEST['guess'])) {				
				if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
				if(!empty($errors))break;
				$_SESSION['GuessGame']->makeGuess($_REQUEST['guess'], $dbconn, $_SESSION['user']);
			}
			// check if game is won
			if($_SESSION['GuessGame']->getState()=="correct"){
				$_SESSION['state']="GuessGameWon";
				$view="GuessGameWon.php";
			}
			
			$_REQUEST['guess']="";
			break;

		
		case "GuessGameWon":
			// default view
			$view="GuessGameWon.php";
				
			if(!empty($_REQUEST['newgame'])) {
				$_REQUEST['newgame'] = "";
				$_SESSION['GuessGame']->newGame($dbconn, $_SESSION['user']);
				$_SESSION['state']="GuessGame";
				$view="GuessGame.php";
				break;
			}
			
			$errors[]="Invalid request";
			break;

		
		case "15Puzzle":
			$view="15Puzzle.php";
			
			if(!isset($_SESSION['15Puzzle'])) $_SESSION['15Puzzle']=new Puzzle15($dbconn, $_SESSION['user']);

			if(!empty($_REQUEST['newgame'])){
				$_REQUEST['newgame'] = "";
				$_SESSION["15Puzzle"]->newGame($dbconn, $_SESSION['user']);
				break;
			}
			
			if(!empty($_REQUEST['tile'])){
				$_SESSION["15Puzzle"]->move($_REQUEST['tile'], $dbconn, $_SESSION['user']);
			}
			if($_SESSION["15Puzzle"]->getState()=="win"){
				$_SESSION['state']="15PuzzleWin";
				$view="15PuzzleWin.php";
			}
			break;

	
		case "15PuzzleWin":
			// default view
			$view="15PuzzleWin.php";
			
			if(!empty($_REQUEST['newgame'])) {
				$_REQUEST['newgame'] = "";
				$_SESSION['15Puzzle']->newGame($dbconn, $_SESSION['user']);
				$_SESSION['state']="15Puzzle";
				$view="15Puzzle.php";
				break;
			}
			
			$errors[]="Invalid request";
			break;
		

		case "PegSolitaire":
			// default view
			$view="PegSolitaire.php";
			// create new instance of game
			if(!isset($_SESSION['PegSolitaire'])) $_SESSION['PegSolitaire']=new PegSolitaire($dbconn, $_SESSION['user']);
			
			if(!empty($_REQUEST['newgame'])){
				$_REQUEST['newgame'] = "";
				$_SESSION["PegSolitaire"]->newGame($dbconn, $_SESSION['user']);
				break;
			}
			// handle user move	
			if(!empty($_REQUEST['move'])){
				$_SESSION["PegSolitaire"]->makeMove($_REQUEST['move'], $dbconn, $_SESSION['user']);
			}
			// check if game is over
			if($_SESSION["PegSolitaire"]->getState()!="play"){
				$_SESSION['state']="PegSolitaireOver";
				$view="PegSolitaireOver.php";
			}
			
			$_REQUEST['move']="";
			break;
			
			
		case "PegSolitaireOver":
			// default view
			$view="PegSolitaireOver.php";
			
			if(!empty($_REQUEST['newgame'])) {
				$_REQUEST['newgame'] = "";
				$_SESSION['PegSolitaire']->newGame($dbconn, $_SESSION['user']);
				$_SESSION['state']="PegSolitaire";
				$view="PegSolitaire.php";
				break;
			}
			
			$errors[]="Invalid request";
			break;
		

		case "Mastermind":
			$view="Mastermind.php";
			if(!isset($_SESSION['Mastermind'])) $_SESSION['Mastermind']=new Mastermind($dbconn, $_SESSION['user']);	
			if($_SESSION["Mastermind"]->getState()=="win"){
				$_SESSION['state']="MastermindWin";
				$view="MastermindWin.php";
			}else if($_SESSION["Mastermind"]->getState()=="lose"){
				 $_SESSION['state']="MastermindLose";
				 $view="MastermindLose.php";
			}	
			if(empty($_REQUEST['mmclick']) && empty($_REQUEST['control'])){
				       break;
			}
			if(empty($_REQUEST['mmclick'])==false){
				$_SESSION["Mastermind"]->choose($_REQUEST['mmclick']);
			}else if($_REQUEST['control']=="delete"){
				$_SESSION["Mastermind"]->delete($_REQUEST['control']);
			}else if($_REQUEST['control']=="getfeedback"){
				$_SESSION["Mastermind"]->getfeedback($dbconn, $_SESSION['user']);
			}else if($_REQUEST['control']=="Quit"){
				$_SESSION['state']="unavailable";
				$view="unavailable.php";
			}else if($_REQUEST['control']=="Start again"){
				$_SESSION['Mastermind']=new Mastermind($dbconn, $_SESSION['user']);
			}

			if($_SESSION["Mastermind"]->getState()=="win"){
				$_SESSION['state']="MastermindWin";
				$view="MastermindWin.php";
			}
			else if($_SESSION["Mastermind"]->getState()=="lose"){
				$_SESSION['state']="MastermindLose";
				$view="MastermindLose.php";
			}
			break;
		
		case "MastermindWin":
			// default view
			$view="MastermindWin.php";			
			if(empty($_REQUEST['control'])){
				break;
			}
			if($_REQUEST['control']=="Start again"){
				$_SESSION['Mastermind']=new Mastermind($dbconn, $_SESSION['user']);
				$_SESSION['state']="Mastermind";
				$view="Mastermind.php";
				break;
			}
			$errors[]="Invalid request";
			break;

		case "MastermindLose":
			$view="MastermindLose.php";
			if(empty($_REQUEST['control'])){
				break;
			}
			if($_REQUEST['control']=="Start again"){
				$_SESSION['Mastermind']=new Mastermind($dbconn, $_SESSION['user']);
				$_SESSION['state']="Mastermind";
				$view="Mastermind.php";
				break;
			}
			$errors[]="Invalid request";
			break;
		default:
			$view="unavailable.php";
			break;	
	}
	require_once "view/$view";
?>
