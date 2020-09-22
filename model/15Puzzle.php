<?php
class Puzzle15 {
	public $list=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,0);     //this game array for 15puzzle
	public $state = "";
	public $numMoves = 0;
	
	public function __construct($dbconn, $user) {
		shuffle($this->list);
		while(!$this->validBoard($this->list)) {
			shuffle($this->list);
		}
		$query = "UPDATE puzzle15score SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
	}
	
	public function move($piece, $dbconn, $user){
		if($piece==0){
			return;
		}
		$pieceindex=array_search($piece,$this->list);
		$zeroindex=array_search(0,$this->list);
		if((abs($pieceindex-$zeroindex)==4 || abs($pieceindex-$zeroindex)==1) && ($pieceindex+$zeroindex!=7 && $pieceindex+$zeroindex!=15 && $pieceindex+$zeroindex!=23)){
			$this->numMoves++;
			$this->list[$zeroindex]=$this->list[$pieceindex];
			$this->list[$pieceindex]=0;            //switch the blank with the one moved
		}
		if($this->list==[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,0]){
			$this->state="win";
			$best = pg_fetch_row(pg_query($dbconn, "SELECT bestscore FROM puzzle15score WHERE userid='".$user."'"))[0];
			if ($best=="" || $best > $this->numMoves) pg_query($dbconn, "UPDATE puzzle15score SET bestscore = ".$this->numMoves." WHERE userid='".$user."'");
			$query = "UPDATE puzzle15score SET numwins = numwins + 1 WHERE userid='".$user."'";
			$result = pg_query($dbconn, $query);
		}

	}
	
	// Solvable if:
	// - list contains an odd number of inversions and blank in even row, OR
	// - list contains an even number of inversions and blank in odd row.
	// [Referenced geeksforgeeks.org/check-instance-15-puzzle-solvable/]
	public function validBoard($list){
		$row = intval(array_search(0,$list,true)/4) % 2;
		$inv = $this->numInversions($list) % 2;
		if (($row == 1 && $inv == 0) || ($row == 0 && $inv == 1)) return True;
		return False;
		
	}
	// Do not count 0 in number of Inversions
	public function numInversions($list){
		$inv = 0;
		for ($i = 0; $i < 15; $i++) {
			for ($j = $i+1; $j < 16; $j++) {
				if ($list[$i] > $list[$j] && $list[$i]!=0 && $list[$j]!=0) $inv++;
			}
		}
		return $inv;
	}
	
	public function getarray(){
		return $this->list;
	}

	public function getState(){
		return $this->state;
	}	
	
	public function getNumMoves(){
		return $this->numMoves;
	}

	public function newGame($dbconn, $user){
		$this->state = "";
		$this->numMoves = 0;
		$this->list = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,0);
		shuffle($this->list);
		while(!$this->validBoard($this->list)) {
			shuffle($this->list);
		}
		$query = "UPDATE puzzle15score SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
	}


}
?>

