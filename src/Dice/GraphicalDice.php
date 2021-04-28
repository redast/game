<?php

declare(strict_types=1);

namespace Redast\Dice;

/**
 * Class DiceGraphic
 */
class GraphicalDice extends Dice
{
    const SIDES = 6;
    const SIDE_1 = '
    <div class="first-face">
        <span class="pip"></span>
    </div>';
    const SIDE_2 = '
    <div class="second-face">
        <span class="pip"></span>
        <span class="pip"></span>
    </div>';
    const SIDE_3 = '
    <div class="third-face">
        <span class="pip"></span>
        <span class="pip"></span>
        <span class="pip"></span>
    </div>';
    const SIDE_4 = '
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
    const SIDE_5 = '
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
    const SIDE_6 = '
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

    public function __construct()
    {
        parent::__construct(self::SIDES);
    }

    public function graphic(): string
    {
        $face = $this->getLastRoll();
        $graphic = "";
        switch ($face) {
            case 1:
                $graphic = self::SIDE_1;
                break;
            case 2:
                $graphic = self::SIDE_2;
                break;
            case 3:
                $graphic = self::SIDE_3;
                break;
            case 4:
                $graphic = self::SIDE_4;
                break;
            case 5:
                $graphic = self::SIDE_5;
                break;
            case 6:
                $graphic = self::SIDE_6;
                break;
        }
        return $graphic;
    }
}
