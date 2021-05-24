<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);
use Mos\Controller\Yatzy;

$header = $header ?? null;
$message = $message ?? null;

?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<main>
<form method="post" class="yatzy-form" action="<?= $action ?>">

    <fieldset>
        <legend>Start Yatzy game:</legend>

    <input type="submit" name="start" value="Start">
    </fieldset>
</form>
</main>