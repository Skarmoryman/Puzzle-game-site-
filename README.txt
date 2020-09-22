A website that hosts several interactive puzzle games - uses PHP complete with profile creation and database support.
Cannot run in current state as database requires university password, but the code is quite intuitive to read.


Architected so that it uses:

1) Model, View, Controller
2) A Front Controller which implements a finite state machine.
	All requests go through index.php

This combination allows

a) Separation of concerns (M/V/C)
b) The application can move from any page to any other page
c) Easy to extend and expand code
e) Self documenting code. Nothing is hidden more than a file away.

