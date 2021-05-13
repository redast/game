<?php

declare(strict_types=1);

//namespace Redast\form;

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

?>
<h1>Play game 21</h1>
<main>
<form method="post" class="21-form" action="<?= $action ?>">

    <fieldset>
        <legend>Chose how many dice you want to play with:</legend>
        <input type="radio" id="one" name="diceCount" value="1">
        <label for="1">1</label><br>
        <input type="radio" id="two" name="diceCount" value="2">
        <label for="2">2</label><br>
        <input type="submit" name="submit" value="Send">
    </fieldset>
</form>
</main>
