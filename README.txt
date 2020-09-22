A website that hosts several interactive puzzle games - uses PHP complete with profile creation and database support.
The puzzle games are Guess game(guess a number), 15 puzzle, peg solitaire, and Mastermind. 

Description of the games:
Guess game - Just guess the number, complete luck
15puzzle - Square board with one empty space. Numbered tiles are randomized, goal of the game is to get all of the tiles in numeric order from 1-15. Tiles can only slide to the empty space. 
Peg solitaire - a peg can be eliminated by another peg moving over it. Goal is to have the least amount of pegs remaining when you cannot eliminate any more.
Mastermind - Game of logic. 10 rounds to guess the right 4 colours. After every round, there is cryptic feedback which you will use to improve your next guesses.


**---Cannot run in current state as database requires university password, but the code is quite intuitive to read.---**

*index.php is the main controller. View and model code is stored in respective folders.


Architected so that it uses:

1) Model, View, Controller
2) A Front Controller which implements a finite state machine.
	All requests go through index.php

This combination allows

a) Separation of concerns (M/V/C)
b) The application can move from any page to any other page
c) Easy to extend and expand code
e) Self documenting code. Nothing is hidden more than a file away.

