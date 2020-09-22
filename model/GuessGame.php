<?php

class GuessGame {
	public $secretNumber = 5;
	public $numGuesses = 0;
	public $history = array();
	public $state = "";

	public function __construct($dbconn, $user) {
        	$this->secretNumber = rand(1,10);
		$query = "UPDATE guessgamescore SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
    	}
	
	public function makeGuess($guess, $dbconn, $user){
		$this->numGuesses++;
		if($guess>$this->secretNumber){
			$this->state="too high";
		} else if($guess<$this->secretNumber){
			$this->state="too low";
		} else {
			$this->state="correct";
			$best = pg_fetch_row(pg_query($dbconn, "SELECT bestscore FROM guessgamescore WHERE userid='".$user."'"))[0];
			
			if ($best == "" || $best > $this->numGuesses) pg_query($dbconn, "UPDATE guessgamescore SET bestscore = ".$this->numGuesses." WHERE userid='".$user."'");
			$query = "UPDATE guessgamescore SET numwins = numwins + 1 WHERE userid='".$user."'";
			$result = pg_query($dbconn, $query);
		}
		$this->history[] = "Guess #$this->numGuesses was $guess and was $this->state.";
	}
	
	public function newGame($dbconn, $user){
		$this->numGuesses = 0;
		$this->history = array();
		$this->state = "lmao";
        	$this->secretNumber = rand(1,10);
		$query = "UPDATE guessgamescore SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
	}

	public function getState(){
		return $this->state;
	}
}
?>
