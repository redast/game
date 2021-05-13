<?php

declare(strict_types=1);

use Mos\Controller\Game;

use function Mos\Functions\url;

$header = $header ?? null;
$message = $message ?? null;

?>

<h1><?= $header ?></h1>
<p><?= $message ?></p>

<?php

if ($gameOver == true) {
    $playAgain = '<a href="';
    $playAgain .= url('/form/view');
    $playAgain .= '">Play again</a>';

    $startOver = '<a href="';
    $startOver .= url('/dice/reset');
    $startOver .= '">Start over</a>';

    echo "<p>$playAgain  $startOver</p>";

    if ($playerWon == true) {
        echo '<h1>You won!!</h1>';
    } else if ($houseWon == true) {
        echo '<h1>House won!!</h1>';
    } else {
        echo '<h1>Game over!</h1>';
        echo '<h1>Nobody won</h1>';
    }
    echo "<h2>You: $playerScore - $houseScore House</h2>";
    echo "<h2>You:</h2>";
    echo "<h3>Sum: $playerSum </h3>";
    $playerRollCount = count($playerDigits);
    echo "<h3>Rolls: $playerRollCount </h3>";
    echo "<h2>House:</h2>";
    echo "<h3>Sum: $houseSum </h3>";
    $houseRollCount = count($houseDigits);
    echo "<h3>Rolls: $houseRollCount </h3>";
    echo "<br>";

    echo "<h2>You:</h2>";
    echo "<div class='die'>$playerFaces</div>";

    echo "<h2>House:</h2>";
    echo "<div class='die'>$houseFaces</div>";
} else {
    $endPoint = '<FORM method="post" action="';
    $endPoint .= url('/dice');
    $endPoint .= '">';
    echo $endPoint;

    if (isset($_POST['stop'])) {
        echo '
        <input
            type="button"
            value="Roll dice again"
            onClick="history.go(0)"
            disabled
        />';
    } else {
        echo '
        <input
            type="button"
            value="Roll dice again"
            onClick="history.go(0)"
        />';
    }

    echo "<INPUT type='submit' name='stop' value='Stop here'>";
    echo "</FORM>";
    echo "<h2>You: $playerScore - $houseScore House</h2>";
    echo "<p>Your dicehand:</p>";
    echo "<p>Points sum: $playerSum</p>";
    $playerRollCount = count($playerDigits);
    echo "<p>Total rolls: $playerRollCount </p>";
    echo "<div class='die'>$playerFaces</div>";
/* 
    echo "<p>House dicehand:</p>";
    echo "<p>Points sum: $houseSum</p>";
    $houseRollCount = count($houseDigits);
    echo "<p>Total rolls: $houseRollCount </p>";
    echo "<div class='die'>$houseFaces</div>"; */
}
?>
