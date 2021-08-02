<?php

// any debug/warnings rendered in the page output would break the json format being returned.
// they should just be logged to file instead.
ini_set('display_errors', 0);
header('Content-Type: application/json');

include "model.php";

if (isset($_GET["end"])) {
  end_session();
  fail_with("Session ended as requested");
}

// Multiplayer
if (isset($_GET["invite"])) {
  session_id($_GET["invite"]);
  setcookie("player", 2);
  $player = 2;
} elseif (!isset($_COOKIE["player"])) {
  setcookie("player", 1);
  $player = 1;
} else {
  $player = $_COOKIE["player"];
}

session_start();

// BOARD SETUP/INIT
if (!isset($_SESSION["data"])) {
  // invalid invite
  if (isset($_GET["invite"])) {
    end_session();
    fail_with("Invalid invite code");
  }
  // create boards
  if (!isset($_GET["size"])) {
    end_session();
    fail_with("The game has ended");
  }
  $board_size = $_GET["size"];
  if ($board_size < 5 || $board_size > 10) {
    end_session();
    fail_with("Invalid board size");
  }
  $boards = array(
    1 => new Board($board_size),
    2 => new Board($board_size)
  );
  $boards[1]->my_turn = true;
} else {
  // load boards
  $boards = unserialize($_SESSION["data"]);
}
$my_board = $boards[$player];
$opponent_board = $boards[$player == 2 ? 1 : 2];

// prevent multiple people joining with the invite link - only accept it once
if(isset($_GET["invite"]) && $my_board->name) {
  end_session(false);
  fail_with("Invite already used");
}

// ACTIONS

// Set/update player name
if (isset($_GET["name"])) {
  $boards[$player]->name = $_GET["name"];
}

// Make a move
if (isset($_GET["x"]) && isset($_GET["y"])) {
  if (!$my_board->is_alive() || !$opponent_board->is_alive()) {
    fail_with("A player has won already!");
  }
  if (!$my_board->my_turn) {
    fail_with("It isn't your turn");
  }
  $opponent_board->strike(new Point($_GET["x"], $_GET["y"]));
  $my_board->my_turn = false;
  $opponent_board->my_turn = true;
}

// Check win status
$winner = null;
if (!$my_board->is_alive()) $winner = false;
if (!$opponent_board->is_alive()) $winner = true;
if ($winner != null) {
  $my_board->my_turn = false;
  $opponent_board->my_turn = false;
}

// SEND STATE
echo json_encode(array(
  "invite" => session_id(),
  "opponent" => $opponent_board->name,
  "winner" => $winner,
  "my_board" => $my_board,
  "my_hits" => $opponent_board->get_hits(),
  "my_misses" => $opponent_board->get_misses(),
  "my_last_strike" => $opponent_board->last_strike != null && $opponent_board->last_strike['name'] != null
));

// SAVE STATE
$_SESSION["data"] = serialize($boards);
