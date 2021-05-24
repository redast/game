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
class Yatzy
{
    public function view(): ResponseInterface
    {
        $data = [
            "header" => "Yatzy",
            "message" => "Play yatzy game here!",
            "action" => url("/yatzy-form/process"),
            "start" => $_SESSION["start"] ?? null,
        ];
        $body = renderView("layout/yatzy-form.php", $data);

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function process(): ResponseInterface
    {
        $_SESSION["start"] = $_POST["start"] ?? null;

        $psr17Factory = new Psr17Factory();
        return $psr17Factory
            ->createResponse(301)
            ->withHeader("Location", url("/yatzy/play"));
    }
}
