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
 * Class DiceGraphic
 */
class GraphicalDice extends Dice
{

    const SIDES = 6;

     public function __construct()
    {
        parent::__construct(self::SIDES);
    }

    public function graphic(): string
    {

        return "dice-" . $this->getLastRoll();

    }
}
