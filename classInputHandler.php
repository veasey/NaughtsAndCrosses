<?php

class InputHandler {

  private $eolCount = 0; // keep track of enter presses
  private $line = "";
  private $game = [];
  private $complete = false;

  // does user want to quit?
  public function quit($input) {
    $input = trim($input);
    $input = strtolower($input);

    if ($input == 'q' || $input == 'quit' || $input == 'exit') {
      return true;
    }

    return false;
  }

  // is this newline number one, or three?
  public function newLine() {

    $this->eolCount++;
    // save move line
    if ($this->eolCount >= 2) {

      $this->eolCount = 0;

      // save whole game
      $this->complete = true;
    }
  }

  // check moves are valid
  public function getMoves($input) {

    $valid = true;

    $input = str_split(strtolower(trim($input)));
    if (count($input) != 3) {
      $valid = false;
    }

    foreach ($input as $move) {

      // convert zeros to 'o's for sanity
      if ($move == '0') {
        $move = 'o';
      }

      if ($move != 'x' && $move != 'o') {
        $valid = false;
      }

      $this->game[] = $move;
    }

    if (!$valid) {
      $this->game = [];
      $this->complete = false;
      throw new Exception('Invalid moves.');
    }
  }

  // has the game ended?
  public function gameComplete() {
    return $this->complete;
  }

  // return moves, reset game
  public function getGame() {

    $game = $this->game;

    $this->game = [];
    $this->complete = false;

    return $game;
  }
}
