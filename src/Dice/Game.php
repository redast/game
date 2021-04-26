<?php

declare(strict_types=1);

namespace Redast\Dice;
use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    url
};

use Redast\Dice\Dice;
use Redast\Dice\DiceHand;
use Redast\Dice\GraphicalDice;

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
        $die_count = $_POST["die_count"] ?? null;
        $submit = $_POST["submit"] ?? null;
        $stop = $_POST["stop"] ?? null;
        $rollPC = $_POST["rollPC"] ?? null;

        global $gamersSum;
        $gamersSum = "";
        $gameover = "";
        $die_count = (int)$die_count;

        if ( isset( $_POST['submit'] ) ) {
            if (!isset($_SESSION["die_count"])) {
                $_SESSION["die_count"] = $die_count;
                $_SESSION["game_over"] = FALSE;
                $_SESSION["player_die"] = "";
                $_SESSION["player_sum"] = 0;
                $_SESSION["player_rolls"] = [];
                $_SESSION["player_won"] = NULL;
                $_SESSION["house_die"] = "";
                $_SESSION["house_sum"] = 0;
                $_SESSION["house_rolls"] = [];
                $_SESSION["house_won"] = NULL;
            }

            $diceHand = new DiceHand($die_count);
            $diceHand->roll();
            $faces = $diceHand->getLastRoll();
            $sum = array_sum($faces);

            # Check new sum
            $new_sum = $_SESSION["player_sum"] + $sum;
            if ($new_sum >= 21) {
                $_SESSION["player_sum"] += $sum;
                array_push($_SESSION["player_rolls"], $faces);
                $this->processGame();
                $data["die_count"] = $_SESSION["die_count"];
                $data["game_over"] = $_SESSION["game_over"];
                $data["player_die"] = $_SESSION["player_die"];
                $data["player_sum"] = $_SESSION["player_sum"];
                $data["player_rolls"] = $_SESSION["player_rolls"];
                $data["player_won"] = $_SESSION["player_won"];
                $data["house_die"] = $_SESSION["house_die"];
                $data["house_sum"] = $_SESSION["house_sum"];
                $data["house_rolls"] = $_SESSION["house_rolls"];
                $data["house_won"] = $_SESSION["house_won"];

                $body = renderView("layout/dice.php", $data);
                sendResponse($body);
            } else {
                # Game still in progress
                $_SESSION["player_sum"] += $sum;
                array_push($_SESSION["player_rolls"], $faces);

                $data["die_count"] = $_SESSION["die_count"];
                $data["game_over"] = $_SESSION["game_over"];

                $data["player_sum"] = $_SESSION["player_sum"];
                $data["player_rolls"] = $_SESSION["player_rolls"];
                $data["player_won"] = $_SESSION["player_won"];

                $data["house_die"] = $_SESSION["house_die"];
                $data["house_sum"] = $_SESSION["house_sum"];
                $data["house_rolls"] = $_SESSION["house_rolls"];
                $data["house_won"] = $_SESSION["house_won"];

                $data["player_die"] = "";

                for ($i = 0; $i < count($data["player_rolls"]); $i++)
                {
                    $roll = $data["player_rolls"][$i];
                    for ($j = 0; $j <= (int)$die_count-1; $j++)
                    {
                        $data["player_die"] .= $this->showDice($roll[$j]);
                    }
                }

                $_SESSION["player_die"] = $data["player_die"];

                $body = renderView("layout/dice.php", $data);
                sendResponse($body);
            }

        } else if ( isset( $_POST['stop'] ) ) {

            $this->processGame();
            $data["die_count"] = $_SESSION["die_count"];
            $data["game_over"] = $_SESSION["game_over"];
            $data["player_die"] = $_SESSION["player_die"];
            $data["player_sum"] = $_SESSION["player_sum"];
            $data["player_rolls"] = $_SESSION["player_rolls"];
            $data["player_won"] = $_SESSION["player_won"];
            $data["house_die"] = $_SESSION["house_die"];
            $data["house_sum"] = $_SESSION["house_sum"];
            $data["house_rolls"] = $_SESSION["house_rolls"];
            $data["house_won"] = $_SESSION["house_won"];
            $body = renderView("layout/dice.php", $data);
            sendResponse($body);
        }
    }
    function housePlay(int $die_count, int $score): array {
        $pcSum = 0;
        $pcRolls = [];
        $target = 21;
        if ($score < 21)
        {
            $target = $score;
        }
        while($pcSum < $target) {
            $pcDiceHand = new DiceHand($die_count);
            $pcDiceHand->roll();
            $faces = $pcDiceHand->getLastRoll();
            array_push($pcRolls, $faces);
            $sum = array_sum($faces);
            $pcSum += $sum;
        }
        return $pcRolls;
    }
    function processGame(): void {
        # let pc play and fetch pc game results
        $house_rolls = $this->housePlay($_SESSION["die_count"], $_SESSION["player_sum"]);
        $house_sum = 0;
        $house_die = "";
        for ($i = 0; $i < count($house_rolls); $i++)
        {
            $roll = $house_rolls[$i];
            for ($j = 0; $j <= (int)$_SESSION["die_count"]-1; $j++)
            {
                $house_sum += (int)$roll[$j];
                $house_die .= $this->showDice($roll[$j]);
            }
        }

        $player_sum = $_SESSION["player_sum"];
        # Player is not fat
        if ($player_sum <= 21)
        {
            # PC is not fat
            if ($house_sum <= 21)
            {
                # PC >= player
                if ($house_sum >= $player_sum)
                {
                    # PC won
                    $_SESSION["house_won"] = TRUE;
                    $_SESSION["player_won"] = FALSE;
                } else {
                    # Player won
                    $_SESSION["house_won"] = FALSE;
                    $_SESSION["player_won"] = TRUE;
                }
            } else {
                # Player won
                $_SESSION["house_won"] = FALSE;
                $_SESSION["player_won"] = TRUE;
            }
        } else {
            # Player is fat
            # PC is not fat
            if ($house_sum <= 21) {
                # PC won
                $_SESSION["house_won"] = TRUE;
                $_SESSION["player_won"] = FALSE;
            } else {
                # Both are fat
                $_SESSION["house_won"] = FALSE;
                $_SESSION["player_won"] = FALSE;
            }
        }

        $_SESSION["game_over"] = TRUE;
        $_SESSION["house_sum"] = $house_sum;
        $_SESSION["house_rolls"] = $house_rolls;
        $_SESSION["house_die"] = $house_die;
    }
    function showDice(int $face): string {
        $dice_face_1 = '
        <div class="first-face">
            <span class="pip"></span>
        </div>';

        $dice_face_2 = '
        <div class="second-face">
            <span class="pip"></span>
            <span class="pip"></span>
        </div>';

        $dice_face_3 = '
        <div class="third-face">
            <span class="pip"></span>
            <span class="pip"></span>
            <span class="pip"></span>
        </div>';

        $dice_face_4 = '
        <div class="fourth-face">
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
        </div>';

        $dice_face_5 = '
        <div class="fifth-face">
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
            <div class="column">
                <span class="pip"></span>
            </div>
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
        </div>';

        $dice_face_6 = '
        <div class="sixth-face">
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
            <div class="column">
                <span class="pip"></span>
                <span class="pip"></span>
                <span class="pip"></span>
            </div>
        </div>';

        switch ($face) {
            case 1:
                return $dice_face_1;
            case 2:
                return $dice_face_2;
            case 3:
                return $dice_face_3;
            case 4:
                return $dice_face_4;
            case 5:
                return $dice_face_5;
            case 6:
                return $dice_face_6;
        }
    }
}
