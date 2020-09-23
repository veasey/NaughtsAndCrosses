<?php // just loading and saving games

class GameHandler {

  private $conn;

  private static $winRows = array(
		array(1,2,3),
		array(4,5,6),
		array(7,8,9),
		array(1,4,7),
		array(2,5,8),
		array(3,6,9),
		array(1,5,9),
		array(3,5,7)
	);

  function __construct($host, $user, $pass, $database) {
    $this->conn = new mysqli($host, $user, $pass, $database);
  }

  private function findWinner($moves) {

    $winningMovesX = 0;
    $winningMovesO = 0;

    foreach (self::$winRows as $winRow) {
      foreach ($moves as $index => $move) {

        if (in_array($index, $winRow)) {
          if ($move == 'x') {
            $winningMovesX++;
          } else if ($move == 'o') {
            $winningMovesO++;
          }
        }
      }
    }

    if ($winningMovesX > $winningMovesO) {
      return 'x';
    } elseif ($winningMovesX < $winningMovesO) {
      return 'o';
    }

    return 'd';
  }

  public function saveGame($moves) {

    // workout who won
    $winner = $this->findWinner($moves);

    // save moves and who won
    $moves = implode('', $moves);
    $sql = "INSERT INTO games (moves, winner) VALUES ('$moves', '$winner')";
    if ($this->conn->query($sql) !== TRUE) {
      throw new Exception("Error: " . $sql . ": " . $this->conn->error);
    }
  }

  public function getScores() {

    $sql = "SELECT
      COUNT(IF(winner = 'x', 1, NULL)) 'x',
      COUNT(IF(winner = 'o', 1, NULL)) 'o',
      COUNT(IF(winner = 'd', 1, NULL)) 'd'
      FROM games;";

    $results = $this->conn->query($sql);
    if ($results === FALSE) {
      throw new Exception("Error: " . $sql . ": " . $this->conn->error);
    }

    $row = $results->fetch_assoc();
    return $row;
  }
}
