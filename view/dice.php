<?php

declare(strict_types=1);

use Redast\Dice\Game;

use function Mos\Functions\url;

$header = $header ?? null;
$message = $message ?? null;

?>

<h1><?= $header ?></h1>
<p><?= $message ?></p>

<?php

if ($gameOver == true) {
    $endPoint = '<a href="';
    $endPoint .= url('/form');
    $endPoint .= '">Play again</a>';
    echo $endPoint;
    if ($playerWon == true) {
        echo '<h1>Game won!!</h1>';
    } else if ($houseWon == true) {
        echo '<h1>Game over!</h1>';
        echo '<h1>The house won</h1>';
    } else {
        echo '<h1>Game over!</h1>';
        echo '<h1>Nobody won</h1>';
    }
    echo "<h2>You:</h2>";
    echo "<h3>Sum: $playerSum </h3>";
    $playerRollCount = count($playerRollDigits);
    echo "<h3>Rolls: $playerRollCount </h3>";
    echo "<h2>House:</h2>";
    echo "<h3>Sum: $houseSum </h3>";
    $houseRollCount = count($houseRollDigits);
    echo "<h3>Rolls: $houseRollCount </h3>";
    echo "<br>";

    echo "<h2>You:</h2>";
    echo "<div class='die'>$playerRollFaces</div>";

    echo "<h2>House:</h2>";
    echo "<div class='die'>$houseRollFaces</div>";
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

    echo "<p>Dicehand:</p>";
    echo "<p>Points sum: $playerSum</p>";
    $playerRollCount = count($playerRollDigits);
    echo "<p>Total rolls: $playerRollCount </p>";
    echo "<div class='die'>$playerRollFaces</div>";
}
?>
