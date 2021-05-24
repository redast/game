<?php

declare(strict_types=1);

namespace Redast\Dice;

/**
 * Class DiceHand.
 */
class Yatzy extends DiceHand
{

    public function rollDice(&$dice, &$diceToReroll) {
        for ($i = 0; $i < count($diceToReroll); $i++) { 
            $temp = $diceToReroll[$i] - 1;
            $dice[$temp] = mt_rand(1, 6);
        }
        $diceToReroll = array();
        return $diceToReroll;
    }
   /*  public int $diceCount;
    public array $dice;
    public array $rollDigits;
    public string $rollFaces;
    public function __construct(int $diceCount)
    {
        $this->dice = [];
        $this->rollDigits = [];
        $this->rollFaces = "";
        $this->diceCount = $diceCount;
        for ($i = 0; $i < $this->diceCount; $i++) {
            $this->dice[$i] = new GraphicalDice();
        }
    }

    public function roll(): void
    {
        for ($i = 0; $i < $this->diceCount; $i++) {
            $this->dice[$i]->roll();
            $this->rollDigits[$i] =  $this->dice[$i]->getLastRoll();
            $this->rollFaces .= $this->dice[$i]->graphic();
        }
    }

    public function getLastDigits(): array
    {
        return $this->rollDigits;
    }

    public function getLastFaces(): string
    {
        return $this->rollFaces;
    }

    public function getLastSum(): int
    {
        return array_sum($this->rollDigits);
    } */
}
