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
 * Class DiceHand.
 */
class DiceHand
{
    private int $number;
    private array $dice;
    private array $faces;
    public function __construct(int $number)
    {
        $this->number = $number;
        for ($i = 0; $i <= $this->number-1; $i++) {
            $this->dice[$i] = new Dice();
        }
    }

    public function roll(): void
    {
        for ($i = 0; $i <= $this->number-1; $i++) {
            $this->faces[$i] = $this->dice[$i]->roll();
        }
    }

    public function getLastRoll(): array
    {
        if (empty($this->faces)) {
            $this->roll();
        }
        return $this->faces;
    }
}
