<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);
use function Mos\Functions\url;
use Redast\Dice\Game;

$header = $header ?? null;
$message = $message ?? null;
//$stop = $_POST["stop"] ?? null;
$diceHandRoll = $diceHandRoll ?? "You have stopped the game";
$diceHandRoll2 = $diceHandRoll2 ?? "PC havent started game yet";
$sum2 = $sum2 ?? "PC havent started game yet";
//var_dump($_POST["stop"]);
?>
<h1><?= $header ?></h1>
<p><?= $message ?></p>

<?php
    if ( $game_over == TRUE)
    {
        if ( $player_won == TRUE)
        {
            echo '<h1>Game won!!</h1>';
        } else if ($house_won == TRUE) {
            echo '<h1>Game over!</h1>';
            echo '<h1>The house won</h1>';
        }else {
            echo '<h1>Game over!</h1>';
            echo '<h1>Nobody won</h1>';
            echo '<p>bunch of losers..Shame on you!!</p>';
        }
        echo "<h2>You:</h2>";
        echo "<h3>Sum: $player_sum </h3>";
        $player_roll_count = count($player_rolls);
        echo "<h3>Rolls: $player_roll_count </h3>";
        echo "<h2>House:</h2>";
        echo "<h3>Sum: $house_sum </h3>";
        $house_roll_count = count($house_rolls);
        echo "<h3>Rolls: $house_roll_count </h3>";
        echo "<br>";

        echo "<h2>You:</h2>";
        echo "<div class='die'>$player_die</div>";

        echo "<h2>House:</h2>";
        echo "<div class='die'>$house_die</div>";
    } else {
        $end_point = '<FORM method="post" action="';
        $end_point .= url('/dice');
        $end_point .= '">';
        echo $end_point;

        if ( isset( $_POST['stop'] ) )
        {
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
        echo "<p>Points sum: $player_sum</p>";
        $player_roll_count = count($player_rolls);
        echo "<p>Total rolls: $player_roll_count </p>";
        echo "<div class='die'>$player_die</div>";
    }
?>
