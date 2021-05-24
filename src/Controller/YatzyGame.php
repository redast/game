<?php

declare(strict_types=1);

namespace Mos\Controller;

use Redast\Dice\Dice;
use Redast\Dice\DiceHand;
use Redast\Dice\GraphicalDice;
use Redast\Dice\Yatzy;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Mos\Functions\{
    destroySession,
    renderView,
    url
};

/**
 * Controller showing how to work with forms.
 */
class YatzyGame
{
    public function playGame(): ResponseInterface
    {
        $this->initData(); # Check if game in progress else init session variables
        $this->newRound();
        $this->getData();
        $this->playerRoll();

        $body = renderView("layout/yatzy.php", $this->getData());

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
    public function roll(): ResponseInterface
    {
        $_SESSION["rollAgain"] = $_POST["rollAgain"] ?? null;
        $this->getData();
        $this->newRound();
        $roundNumber = $_SESSION["roundNumber"];
        //var_dump($_SESSION["roundNumber"]);
        
        //$this->newRound();
        
        while ($roundNumber < 3) {
            
            $roundNumber++;
            //$this->initRound();
            //$this->newRound();
            //$_SESSION["roundNumber"] == $roundNumber;
            $this->playerRoll();
            
            

        }
        if ($roundNumber >= 3) {
            //$this->initRound();
            $_SESSION["roundOver"] == true;
        }
        //$this->countRoundSum();
        $body = renderView("layout/yatzy.php", $this->getData());

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function rollDice(): DiceHand
    {
        $dice = new Yatzy(5);
        $dice->roll();
        return $dice;
    }


    public function playerRoll(): void
    {   
        $dice = $this->rollDice();
        //$_SESSION["playerSum"] += $dice->getLastSum();
        $_SESSION["playerFaces"] = $dice->getLastFaces();
        $_SESSION["playerDigits"] = $dice->getLastDigits();
    }
/* 
    public function initRound(): void {
        if (!isset($_SESSION["initialized"])) {
            $this->initData();
        } else if ($_SESSION["roundOver"] == false) {
            $this->newRound();
        }
    } */

    public function countRoundSum(): void {

        
    }
    public function newRound(): void {
        $_SESSION["header"] = "Dice";
        $_SESSION["message"] = "Yatzy";
        $_SESSION["initialized"] = true;
        $_SESSION["roundOver"] = false;
        $_SESSION["gameOver"] = false;
        $_SESSION["roundNumber"] = 1;
        $_SESSION["playerFaces"] = "";
        $_SESSION["playerDigits"] = [];
        $_SESSION["diceSum"] = 0;
        $_SESSION["totalSum"] = 0;
        $_SESSION["endSum"] = 0;
    }


    public function getData(): array {
        return [
            "header" => $_SESSION["header"],
            "message" => $_SESSION["message"],
            "initialized" => $_SESSION["initialized"],
            "roundOver" => $_SESSION["roundOver"],
            "gameOver" => $_SESSION["gameOver"],
            "diceSum" => $_SESSION["diceSum"],
            "playerFaces" => $_SESSION["playerFaces"],
            "playerDigits" => $_SESSION["playerDigits"],
            "rollAgain" => $_SESSION["rollAgain"] ?? null,
            "roundNumber" => $_SESSION["roundNumber"]
        ];
    }

    public function initData(): void {
        $roundNumber = $_SESSION["roundNumber"] ?? 1;
        $_SESSION["header"] = "Dice";
        $_SESSION["message"] = "Yatzy";
        $_SESSION["initialized"] = true;
    }

}
