<?php

declare(strict_types=1);

use Mos\Controller\YatzyGame;

use function Mos\Functions\url;

$header = $header ?? null;
$message = $message ?? null;

?>

<h1><?= $header ?></h1>
<p><?= $message ?></p>

<?php

if ($roundOver == true) {
    $endPoint = '<FORM method="post" action="';
    $endPoint .= url("/yatzy/roll");
    $endPoint .= '">';
    echo $endPoint;
    echo "<INPUT type='submit' name='rollAgain' value='Roll again' disabled>";
    echo "<INPUT type='submit' name='playAgain' value='Play again'>";
    echo "</FORM>";
    var_dump($_SESSION["roundNumber"]);
} else {
    $endPoint = '<FORM method="post" action="';
    $endPoint .= url("/yatzy/roll");
    $endPoint .= '">';
    echo $endPoint;
    echo "<INPUT type='submit' name='rollAgain' value='Roll again'>";
    echo "</FORM>";
    var_dump($_SESSION["roundNumber"]);
}
?>
<div class='die'><?= $playerFaces ?></div>
