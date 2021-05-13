<?php

declare(strict_types=1);

namespace Mos\Controller;

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
class Game21
{
    public function view(): ResponseInterface
    {
        $data = [
            "header" => "Form",
            "message" => "Press submit to send the message to the result page.",
            "action" => url("/form/process"),
            "diceCount" => $_SESSION["diceCount"] ?? null,
        ];
        $body = renderView("layout/form.php", $data);

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function process(): ResponseInterface
    {
        $_SESSION["diceCount"] = $_POST["diceCount"] ?? null;

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(301)
            ->withHeader("Location", url("/dice"));
    }
}
