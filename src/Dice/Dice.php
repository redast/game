<?php

declare(strict_types=1);

namespace Redast\Dice;

/* use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
}; */

/**
 * Class Dice.
 */
class Dice
{
    //const FACES = 6;

    //private ?int $roll = null;

    public function __construct(int $faces = 6)
    {
        $this->faces = $faces;
    }

    public function roll(): int
    {
        $this->roll = rand(1, $this->faces);

        return $this->roll;
    }

    public function getLastRoll(): int
    {
        return $this->roll;
    }
}
