<?php
require_once "lib/lib.php";
class Mastermind {
	public $gamelist=array();     //this game array for 15puzzle
	public $feedbacklist=array();
	public $secret=array();
	public $tempfeedback=array();
	public $state = "";
	public $round = 0;
	public $selectnum = 0;
	public $indexcache=array();
	public $secretref=array();
        public function __construct($dbconn, $user) {
		//set up puzzle lists
		for($i=0;$i<10;$i++){
			$this->gamelist[$i]=["white","white","white","white"];
			$this->feedbacklist[$i]=["white","white","white","white"];
		}
		$this->secret=[rand(1,6),rand(1,6),rand(1,6),rand(1,6)]; //need to randomize this
		for($i=0;$i<4;$i++){
			if($this->secret[$i]==1){
				$this->secret[$i]="blue";
			}else if(($this->secret[$i])==2){
				$this->secret[$i]="red";
			}else if(($this->secret[$i])==3){
				$this->secret[$i]="orange";
			}else if(($this->secret[$i])==4){
				$this->secret[$i]="green";
			}else if(($this->secret[$i])==5){
				$this->secret[$i]="purple";
			}else if(($this->secret[$i])==6){
				$this->secret[$i]="brown";
			}
		}
		$this->tempfeedback=["white","white","white","white"];
		$this->indexcache=[null,null,null,null];
		$query = "UPDATE mastermindscore SET numplays = numplays + 1 WHERE userid='".$user."'";
		$result = pg_query($dbconn, $query);
	}

        public function getgamearray(){
                return $this->gamelist;

	}

	public function getfeedbackarray(){
		return $this->feedbacklist;
	}

	public function choose($colour){
		if($this->selectnum>3){
			return;
		}
		$this->gamelist[$this->round][$this->selectnum] = $colour;
		$this->secretref[$this->selectnum]=$colour;
		$this->selectnum++;
	}
	public function delete(){
				if($this->selectnum == 0) return;	
				$this->tempfeedback[$this->selectnum-1]="white";
				$this->gamelist[$this->round][$this->selectnum-1]="white";
				$this->indexcache[$this->selectnum-1]=null;
				$this->selectnum--;
			}

		
	

	public function getState(){
                return $this->state;
	}
	
	public function getSolution(){
		return $this->secret;
	}

	public function getfeedback($dbconn, $user){
		if($this->selectnum!=4){
			return;
		}
		for($i=0;$i<4;$i++){//loop for setting black
			if($this->secretref[$i]==$this->secret[$i]){
				$this->tempfeedback[$i]="black";
			}
		}
		for($i=0;$i<4;$i++){
			if($this->secretref[$i]!=$this->secret[$i]){
				if($this->secretref[$i]==$this->secret[0] && $this->tempfeedback[0]=="white"){
					$this->tempfeedback[0]="gray";
				}else if($this->secretref[$i]==$this->secret[1] && $this->tempfeedback[1]=="white"){
					$this->tempfeedback[1]="gray";
				}else if($this->secretref[$i]==$this->secret[2] && $this->tempfeedback[2]=="white"){
					$this->tempfeedback[2]="gray";
				}else if($this->secretref[$i]==$this->secret[3] && $this->tempfeedback[3]=="white"){
					$this->tempfeedback[3]="gray";
				}
			}
		}
		sort($this->tempfeedback);
		$k=0;
		$transferlist=["white","white","white","white"];
		$this->secretref=[null,null,null,null];
		for($i=0;$i<4;$i++){
			if($this->tempfeedback[$i]!="white"){
				$this->feedbacklist[$this->round][$k]=$this->tempfeedback[$i];
				$k++;
			}
       		
		}
		if($this->tempfeedback==["black","black","black","black"]){
			$this->state="win";

			$best = pg_fetch_row(pg_query($dbconn, "SELECT bestscore FROM mastermindscore WHERE userid='".$user."'"))[0];
			if ($best == "" || $best > $this->round+1) pg_query($dbconn, "UPDATE mastermindscore SET bestscore = 1 + ".$this->round." WHERE userid='".$user."'");
			$query = "UPDATE mastermindscore SET numwins = numwins + 1 WHERE userid='".$user."'";
			$result = pg_query($dbconn, $query);
		}else if($this->round==9){
			$this->state="lose";
		}
		$this->tempfeedback=["white","white","white","white"];
		$this->round++;
		$this->selectnum=0;
		$this->indexcache=[null,null,null,null];

	}
}

