<?php

declare(strict_types=1);

namespace Redast\Dice;

use Redast\Dice\Dice;
use Redast\Dice\DiceHand;
use Redast\Dice\GraphicalDice;

use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    url
};

/**
 * Class Game.
 */
class Game
{
    public function playGame(): void
    {
        $data = [
            "header" => "Dice",
            "message" => "Play game 21 here:",
        ];
        $diceCount = $_POST["diceCount"] ?? null;
        $diceCount = (int)$diceCount;
        $submit = $_POST["submit"] ?? null;
        $stop = $_POST["stop"] ?? null;
        $restart = $_POST["restart"] ?? null;

        if (isset($_POST['submit'])) {
            if (!isset($_SESSION["diceCount"])) {
                # Init game data
                $_SESSION["gameOver"] = false;
                $_SESSION["playerWon"] = null;
                $_SESSION["houseWon"] = null;
                $_SESSION["diceCount"] = $diceCount;
                # Init player data
                $_SESSION["playerSum"] = 0;
                $_SESSION["playerRollFaces"] = "";
                $_SESSION["playerRollDigits"] = [];
                # Init house data
                $_SESSION["houseSum"] = 0;
                $_SESSION["houseRollFaces"] = "";
                $_SESSION["houseRollDigits"] = [];
            }

            # Roll dice
            $diceHand = new DiceHand($diceCount);
            $diceHand->roll();
            $lastRollFaces = $diceHand->getLastRollFaces();
            $lastRollDigits = $diceHand->getLastRollDigits();
            $lastRollSum = $diceHand->getLastRollSum();

            # Check new points sum
            $playerNewSum = $_SESSION["playerSum"] + $lastRollSum;

            # Player is fat
            if ($playerNewSum >= 21) {
                # Insert last roll to player data
                $_SESSION["playerSum"] += $lastRollSum;
                $_SESSION["playerRollFaces"] .= $lastRollFaces;
                array_push($_SESSION["playerRollDigits"], $lastRollDigits);

                # Let house play
                $this->getWinner();

                # Game data
                $data["gameOver"] = $_SESSION["gameOver"];
                $data["playerWon"] = $_SESSION["playerWon"];
                $data["houseWon"] = $_SESSION["houseWon"];
                $data["diceCount"] = $_SESSION["diceCount"];
                # Player data
                $data["playerSum"] = $_SESSION["playerSum"];
                $data["playerRollFaces"] = $_SESSION["playerRollFaces"];
                $data["playerRollDigits"] = $_SESSION["playerRollDigits"];
                # House data
                $data["houseSum"] = $_SESSION["houseSum"];
                $data["houseRollFaces"] = $_SESSION["houseRollFaces"];
                $data["houseRollDigits"] = $_SESSION["houseRollDigits"];

                # Return and render data
                $body = renderView("layout/dice.php", $data);
                sendResponse($body);
            } else {
                # Game still in progress
                # Insert last roll to player data
                $_SESSION["playerSum"] += $lastRollSum;
                $_SESSION["playerRollFaces"] .= $lastRollFaces;
                array_push($_SESSION["playerRollDigits"], $lastRollDigits);

                # Game data
                $data["gameOver"] = $_SESSION["gameOver"];
                $data["playerWon"] = $_SESSION["playerWon"];
                $data["houseWon"] = $_SESSION["houseWon"];
                $data["diceCount"] = $_SESSION["diceCount"];
                # Player data
                $data["playerSum"] = $_SESSION["playerSum"];
                $data["playerRollFaces"] = $_SESSION["playerRollFaces"];
                $data["playerRollDigits"] = $_SESSION["playerRollDigits"];
                # House data
                $data["houseSum"] = $_SESSION["houseSum"];
                $data["houseRollFaces"] = $_SESSION["houseRollFaces"];
                $data["houseRollDigits"] = $_SESSION["houseRollDigits"];

                # Return and render data
                $body = renderView("layout/dice.php", $data);
                sendResponse($body);
            }
        } else if (isset($_POST['stop'])) {
            # Player pressed stop
            # Let house play
            $this->getWinner();

            # Game data
            $data["gameOver"] = $_SESSION["gameOver"];
            $data["playerWon"] = $_SESSION["playerWon"];
            $data["houseWon"] = $_SESSION["houseWon"];
            $data["diceCount"] = $_SESSION["diceCount"];
            # Player data
            $data["playerSum"] = $_SESSION["playerSum"];
            $data["playerRollFaces"] = $_SESSION["playerRollFaces"];
            $data["playerRollDigits"] = $_SESSION["playerRollDigits"];
            # House data
            $data["houseSum"] = $_SESSION["houseSum"];
            $data["houseRollFaces"] = $_SESSION["houseRollFaces"];
            $data["houseRollDigits"] = $_SESSION["houseRollDigits"];

            # Return and render data
            $body = renderView("layout/dice.php", $data);
            sendResponse($body);
        }
    }
    public function housePlay(int $diceCount, int $score): void
    {
        $houseSum = 0;
        $houseRollFaces = "";
        $houseRollDigits = [];
        $target = 21;
        if ($score < 21) {
            # Target is player score if it is under 21
            $target = $score;
        }
        while ($houseSum < $target) {
            # Roll dice until target sum is reached
            $houseDiceHand = new DiceHand($diceCount);
            $houseDiceHand->roll();
            $houseRollFaces .= $houseDiceHand->getLastRollFaces();
            array_push($houseRollDigits, $houseDiceHand->getLastRollDigits());
            $houseSum += $houseDiceHand->getLastRollSum();
        }

        # Store results to session
        $_SESSION["houseSum"] = $houseSum;
        $_SESSION["houseRollFaces"] = $houseRollFaces;
        $_SESSION["houseRollDigits"] = $houseRollDigits;
    }
    public function getWinner(): void
    {
        # let house play
        $this->housePlay($_SESSION["diceCount"], $_SESSION["playerSum"]);

        # Get player and house sums
        $houseSum = $_SESSION["houseSum"];
        $playerSum = $_SESSION["playerSum"];

        # Player is not fat
        if ($playerSum <= 21) {
            # PC is not fat
            if ($houseSum <= 21) {
                # PC >= player
                if ($houseSum >= $playerSum) {
                    # PC won
                    $_SESSION["houseWon"] = true;
                    $_SESSION["playerWon"] = false;
                } else {
                    # Player won
                    $_SESSION["houseWon"] = false;
                    $_SESSION["playerWon"] = true;
                }
            } else {
                # Player won
                $_SESSION["houseWon"] = false;
                $_SESSION["playerWon"] = true;
            }
        } else {
            # Player is fat
            # PC is not fat
            if ($houseSum <= 21) {
                # PC won
                $_SESSION["houseWon"] = true;
                $_SESSION["playerWon"] = false;
            } else {
                # Both are fat
                $_SESSION["houseWon"] = false;
                $_SESSION["playerWon"] = false;
            }
        }
        # Indicate that game is over
        $_SESSION["gameOver"] = true;
    }
}
