<?php
require_once('config.php');
require_once('classInputHandler.php');
require_once('classGameHandler.php');

$inputHandler = new InputHandler();
$gameHandler = new GameHandler($host, $user, $pass, $database);

echo $welcomeText;

// return game results
if (isset($argv[1]) && strtolower($argv[1]) == '-d') {

  $scores = $gameHandler->getScores();

  echo "X Wins: " . $scores['x'] . "\n";
  echo "O Wins: " . $scores['o'] . "\n";
  echo "Draws:  " . $scores['d'] . "\n";
  echo "\n";
  exit;
}

// enter games
while (1) {

  $line = fgets(STDIN);

  // quit
  if ($inputHandler->quit($line)) {
    exit;
  }

  if ($line == PHP_EOL) {

    $inputHandler->newLine();
    if ($inputHandler->gameComplete()) {
      $gameHandler->saveGame($inputHandler->getGame());
      echo "new game: \n";
    }

  } else {
    $inputHandler->getMoves($line);
  }
}
