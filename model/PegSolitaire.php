<?php

class PegSolitaire {
        public $state = "play";
	public $numMoves = 0;
	// Coords of from tile
	public $xF = -1;	
	public $yF = -1;
	/* Standard board
	 * 0: Empty Space
	 * 1: Peg
	 * 2: Non-playable area
	 * 3: User selected Peg
	 */
	public $board = array(  array(2,2,1,1,1,2,2),
				array(2,2,1,1,1,2,2),
				array(1,1,1,1,1,1,1),
				array(1,1,1,0,1,1,1),
				array(1,1,1,1,1,1,1),
				array(2,2,1,1,1,2,2),
				array(2,2,1,1,1,2,2) );

        public function __construct($dbconn, $user) {
		$query = "UPDATE pegsolitairescore SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
        }

        public function makeMove($move, $dbconn, $user){
		$xT = $move%7;
		$yT = intval($move/7);
		if ($this->xF == -1 && $this->board[$yT][$xT] == 1) {
			$this->xF = $xT;
			$this->yF = $yT;
			$this->board[$this->yF][$this->xF] = 3;
		}
		else {
			if ($this->validMove($this->xF, $this->yF, $xT, $yT)) {
				//update board
				$this->board[$this->yF][$this->xF] = 0;	
				$this->board[$yT][$xT] = 1;
				if (abs($this->yF-$yT) == 2){
					$this->board[($this->yF + $yT)/2][$xT] = 0;
				} else {
					$this->board[$yT][($this->xF + $xT)/2] = 0;
				}
				$this->numMoves++;
				//check win
				if($this->gameOver()){
					$numPegs = 32 - $this->numMoves;
					switch($numPegs) {
						case 1:
							$this->state = "Perfection!";
							break;
						case 2:
							$this->state = "Well Done!";
							break;
						case 3:
							$this->state = "Good Job.";
							break;
						default:
							$this->state = "You can do better.";
							break;
					}
	
					$best = pg_fetch_row(pg_query($dbconn, "SELECT bestscore FROM pegsolitairescore WHERE userid='".$user."'"))[0];

					if ($best == "" || $best > 32-$this->numMoves) pg_query($dbconn, "UPDATE pegsolitairescore SET bestscore = 32 - ".$this->numMoves." WHERE userid='".$user."'");
					$query = "UPDATE pegsolitairescore SET numwins = numwins + 1 WHERE userid='".$user."'";	
					$result = pg_query($dbconn, $query);
				}
			} else {
				$this->board[$this->yF][$this->xF]=1;	
			}
			$this->xF = -1;
			$this->yF = -1;
		}
        }
	
	public function gameOver(){
		//for each row
		for ($i=0; $i<7; $i++){
			//for each element in row $i
			for($j=0; $j<7; $j++){
				if ($this->board[$i][$j] == 1) {
					
					//We do not need to worry about overflow $j as if it does
					//occur, it will be in a differnt column and get rejected.
					if ($this->validMove($j, $i, $j, $i-2) ||
					    $this->validMove($j, $i, $j, $i+2) ||
					    $this->validMove($j, $i, $j-2, $i) ||
					    $this->validMove($j, $i, $j+2, $i)) {
						return False;
					}
				}
			}
		}
		return True;
	}
	
	public function validMove($xF, $yF, $xT, $yT){
		$from = $xF + $yF*7;
		$to = $xT + $yT*7;	
		//check in play space
		if ($xF < 0 || $xF > 6 || 
			$yF < 0 || $yF > 6 || 
			$xT < 0 || $xT > 6 || 
			$yT < 0 || $yT > 6 ) {
			return False;
		}
		//check moveFrom=1 or 3 (for user selected) and moveTo=0
		if (($this->board[$yF][$xF]%2 != 1) || ($this->board[$yT][$xT] != 0)){
			return False;
		}
		//check 2 away from each other (manhattan) and lie on same row/column
		if ((abs($xF-$xT) + abs($yF-$yT) != 2) || ((abs($xF-$xT) != 0) && (abs($yF-$yT) != 0))){
			return False;
		}
		//check between is 1
		if (abs($xF-$xT) == 0 && $this->board[($yF+$yT)/2][$xF] == 1) {
			return True;
		} else if (abs($yF-$yT) == 0 && $this->board[$yF][($xF+$xT)/2] == 1){
			return True;
		}

		return False;
	}
	
	public function boolSelectFrom(){
		if ($this->xF == -1) {
			return True;
		} else {
			return False;
		}
	}

	public function getBoard(){
		return $this->board;
	}

        public function getState(){
                return $this->state;
        }
	
	public function getNumMoves(){
		return $this->numMoves;
	}

        public function newGame($dbconn, $user){		
        	$this->state = "play";
		$this->numMoves = 0;
		$this->xF = -1;	
		$this->yF = -1;
		$this->board = array(   array(2,2,1,1,1,2,2),
					array(2,2,1,1,1,2,2),
					array(1,1,1,1,1,1,1),
					array(1,1,1,0,1,1,1),
					array(1,1,1,1,1,1,1),
					array(2,2,1,1,1,2,2),
					array(2,2,1,1,1,2,2) );
		$query = "UPDATE pegsolitairescore SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
        }

}
?>

