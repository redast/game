<?php

declare(strict_types=1);

namespace Mos\Controller;

use Redast\Dice\Dice;
use Redast\Dice\DiceHand;
use Redast\Dice\GraphicalDice;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    destroySession,
    url
};

/**
 * Class Game.
 */
class Game
{
    public function playGame(): ResponseInterface
    {
        $this->initRound();
        $this->playerRoll();
        $this->houseRoll();
        $this->getWinner();

        $body = renderView("layout/dice.php", $this->getData());
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function stopGame(): ResponseInterface
    {
        $this->houseFinish();
        $this->getWinner();
        $body = renderView("layout/dice.php", $this->getData());
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function resetGame(): ResponseInterface
    {
        session_destroy();
        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(301)
            ->withHeader("Location", url("/form/view"));
    }

    public function rollDice(): DiceHand
    {
        $dice = new DiceHand((int)$_SESSION["diceCount"]);
        $dice->roll();
        return $dice;
    }

    public function playerRoll(): void
    {
        $dice = $this->rollDice();
        $_SESSION["playerSum"] += $dice->getLastSum();
        $_SESSION["playerFaces"] .= $dice->getLastFaces();
        array_push($_SESSION["playerDigits"], $dice->getLastDigits());
    }

    public function houseRoll(): void
    {
        $playerRolls = count($_SESSION["playerDigits"]);
        $houseRolls = count($_SESSION["houseDigits"]);

        if ($houseRolls + 1 < $playerRolls)
        {
            $this->housePlay();
        }
    }

    public function housePlay(): void {
        $dice = $this->rollDice();
        $_SESSION["houseSum"] += $dice->getLastSum();
        $_SESSION["houseFaces"] .= $dice->getLastFaces();
        array_push($_SESSION["houseDigits"], $dice->getLastDigits());
    }

    public function houseFinish(): void {
        $_SESSION["gameOver"] = true;
        while ($_SESSION["houseSum"] < 17) {
            $this->housePlay();
        }
    }

    public function initRound(): void {
        if (!isset($_SESSION["initialized"])) {
            $this->initData();
        } else if ($_SESSION["gameOver"] == true) {
            $this->newRound();
        }
    }

    public function newRound(): void {
        $_SESSION["gameOver"] = false;
        $_SESSION["playerWon"] = null;
        $_SESSION["houseWon"] = null;
        $_SESSION["playerSum"] = 0;
        $_SESSION["playerFaces"] = "";
        $_SESSION["playerDigits"] = [];
        $_SESSION["houseSum"] = 0;
        $_SESSION["houseFaces"] = "";
        $_SESSION["houseDigits"] = [];
    }

    public function initData(): void {
        $this->newRound();
        $diceCount = $_SESSION["diceCount"] ?? 1;
        $diceCount = (int)$diceCount;
        $_SESSION["header"] = "Dice";
        $_SESSION["message"] = "Game 21";
        $_SESSION["initialized"] = true;
        $_SESSION["diceCount"] = $diceCount;
        $_SESSION["playerScore"] = $_SESSION["playerScore"] ?? 0;
        $_SESSION["houseScore"] = $_SESSION["houseScore"] ?? 0;
    }

    public function getData(): array {
        return [
            "header" => $_SESSION["header"],
            "message" => $_SESSION["message"],
            "initialized" => $_SESSION["initialized"],
            "gameOver" => $_SESSION["gameOver"],
            "playerWon" => $_SESSION["playerWon"],
            "houseWon" => $_SESSION["houseWon"],
            "diceCount" => $_SESSION["diceCount"],
            "playerSum" => $_SESSION["playerSum"],
            "playerFaces" => $_SESSION["playerFaces"],
            "playerDigits" => $_SESSION["playerDigits"],
            "playerScore" => $_SESSION["playerScore"],
            "houseSum" => $_SESSION["houseSum"],
            "houseFaces" => $_SESSION["houseFaces"],
            "houseDigits" => $_SESSION["houseDigits"],
            "houseScore" => $_SESSION["houseScore"],
        ];
    }

    public function getWinner(): void {

        # Player is bust
        if ($_SESSION["playerSum"] > 21) {
            $this->houseFinish();
        }

        # Player or House is bust
        # Round is over
        if ($_SESSION["gameOver"] == true) {

            if ($_SESSION["playerSum"] > 21) {
                $_SESSION["playerWon"] = false;
                if ($_SESSION["houseSum"] > 21) {
                    $_SESSION["houseWon"] = false;
                } else {
                    $_SESSION["houseWon"] = true;
                }
            } else {
                if ($_SESSION["houseSum"] > 21) {
                    $_SESSION["houseWon"] = false;
                    $_SESSION["playerWon"] = true;
                } else {
                    if ($_SESSION["houseSum"] >= $_SESSION["playerSum"]) {
                        $_SESSION["houseWon"] = true;
                        $_SESSION["playerWon"] = false;
                    } else {
                        $_SESSION["houseWon"] = false;
                        $_SESSION["playerWon"] = true;
                    }
                }
            }

            # Add round scores
            if ($_SESSION["houseWon"] == true) {
                $_SESSION["houseScore"] += 1;
            }

            if ($_SESSION["playerWon"] == true) {
                $_SESSION["playerScore"] += 1;
            }

            
        }
    }
}
