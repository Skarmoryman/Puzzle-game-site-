drop trigger if exists appuserGameScoresTrigger ON appuser;
drop function if exists appuserGameScoresTriggerFunc;
drop table if exists GuessGameScore cascade;
drop table if exists Puzzle15Score cascade;
drop table if exists PegSolitaireScore cascade;
drop table if exists MastermindScore cascade;
drop table if exists appuser cascade;

create table appuser (
	userid varchar(50) primary key,
	password varchar(255) NOT NULL
	
);

create table GuessGameScore (
	userid varchar(50) primary key,
	numPlays int DEFAULT 0,
	numWins int DEFAULT 0,
	bestScore int,
	CONSTRAINT appuserGuessGameScore_FK FOREIGN KEY (userid) REFERENCES appuser(userid)
		ON DELETE CASCADE
);

CREATE TABLE Puzzle15Score (
	userid varchar(50) primary key,
	numPlays int DEFAULT 0,
	numWins int DEFAULT 0,
	bestScore int,
	CONSTRAINT appuserPuzzle15Score_FK FOREIGN KEY (userid) REFERENCES appuser(userid)
		ON DELETE CASCADE
);

CREATE TABLE PegSolitaireScore (
	userid varchar(50) primary key,
	numPlays int DEFAULT 0,
	numWins int DEFAULT 0,
	bestScore int,
	CONSTRAINT appuserPegSolitaireScore_FK FOREIGN KEY (userid) REFERENCES appuser(userid)
		ON DELETE CASCADE
);
CREATE TABLE MastermindScore (
	userid varchar(50) primary key,
	numPlays int DEFAULT 0,
	numWins int DEFAULT 0,
	bestScore int,
	CONSTRAINT appuserMastermindScore_FK FOREIGN KEY (userid) REFERENCES appuser(userid)
		ON DELETE CASCADE
);

CREATE FUNCTION appuserGameScoresTriggerFunc()
RETURNS trigger AS $$
BEGIN
	INSERT INTO GuessGameScore (userid) VALUES (new.userid);	
	INSERT INTO Puzzle15Score (userid) VALUES (new.userid);
	INSERT INTO PegSolitaireScore (userid) VALUES (new.userid);
	INSERT INTO MastermindScore (userid) VALUES (new.userid);
	RETURN NULL;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER appuserGameScoresTrigger AFTER INSERT ON appuser
	FOR EACH ROW
		EXECUTE PROCEDURE appuserGameScoresTriggerFunc();

insert into appuser values('auser', 'apassword');



