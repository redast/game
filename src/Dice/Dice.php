<?php

declare(strict_types=1);

namespace Redast\Dice;

/**
 * Class Dice.
 */
class Dice
{
    private int $faces;
    private int $lastRoll;
    public function __construct(int $faces = 6)
    {
        $this->faces = $faces;
        $this->lastRoll = 0;
    }

    public function roll(): void
    {
        $this->lastRoll = rand(1, $this->faces);
    }

    public function getLastRoll(): int
    {
        if ($this->lastRoll == 0) {
            $this->roll();
        }
        return $this->lastRoll;
    }
}
