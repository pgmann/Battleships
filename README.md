# Battleships
## Overview
A player vs player networked battleships game. Face off against your opponent and try to sink their ships before they sink yours!

## Frontend
* **Files**: index.php, play.php, scripts/\*, css/\*
* **Libraries**: Bootstrap, jQuery, qrcode.js
* Lobby (index.php)
  * The purpose of this file is to link the 2 players together inside a single PHP session
  * Player 1 creates the game which calls data.php to retrieve an invite code the other player can use
  * Player 2 receives a QR or link from player 1
    * The invite link is validated and error message is shown to the user if it's invalid/already used
  * Once pairing is successful, both are redirected to the play.php page
* Game (play.php)
  * This file is the frontend of the actual game
  * Displays 2 game boards
    * Blue is empty ocean, Light Grey is your own ships, Dark Grey is a miss, Red is a hit
    * The current player's turn is indicated with a highlight around their game board
    * Hover (touch and drag on mobile) on squares to see informative tooltips with ship names, coordinates and meaning
    * Mobile view is optimised via css to display 1 grid at a time with a button to toggle between boards
  * Game status is displayed at the bottom
    * Can indicate whose turn it is, your opponent's last move (including the name of any hit ship) or whether your last move was a hit
    * The game can be ended at any time using the red "End Game" button which takes you back to the lobby (redirects to index.php)
      * The other player will also be kicked out when they next call the backend script

## Backend
* **Files**: data.php, model.php, debug.php
* Networked player vs player
* Create game
  * Uses a new session id as the "invite code"
  * Creates new random game boards for both players based on requested board size
* Join game
  * Join via qr or link
  * Sets the session id to the invite code
  * Validates that the game board exists already (otherwise invalid invite code)
* Model
  * The model.php contains all custom classes used to represent a Point, Ship or Board
  * This file can be included on both the data and debug scripts
  * All model data used to store the state can be serialized/deserialized for storage in the session data
* Anticheat
  * Only required data is sent in data.php
  * Most importantly the placement of the opponent's ships is not revealed - only hit/miss history
* Debugging 
  * There is a secret debug.php script which will reveal ALL game state as json when accessed
  * This includes both game boards so placement of the opponent's ships is visible

## Communication
* AJAX is used for all communication with data.php
  * Any input to the backend is provided as GET query parameters to the data.php script
    * e.g. `data.php?x=1&y=1` to make a move striking B2 (it's zero-indexed)
  * The backend will always return a JSON payload with relevant game state data to update the frontend with
  * Any errors returned will be displayed to the user and appropriate action will be taken
    * For example if the game has ended, the user gets redirected to the data.php script
* The main limitation is that communication must initiate from the client side (AJAX call)
  * This means when a user makes their move there is no way to directly inform the other user it is now their turn
  * To get around this the data script is accessed every second to check for updates (interval could be changed if load is too high)
  * Alternative methods which could have improved this:
    * Long polling: where the server doesn't respond to the client request until there is an update - however this would have blocked a thread which would not be ideal, would also have still required the server to keep checking for an update to the session data it has stored
    * Web sockets: this would normally be my go to approach since it allows full duplex communication between client and server, but was worried this might change the expected assignment outcome too much and would probably require an external PHP library which might be difficult to host in the student environment