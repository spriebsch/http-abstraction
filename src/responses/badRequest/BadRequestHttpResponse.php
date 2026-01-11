<?php declare(strict_types=1);

namespace spriebsch\http;

final class BadRequestHttpResponse extends AbstractHttpResponse
{
    public function __construct()
    {
        parent::__construct('Bad Request');
    }

    public function responseCode(): int
    {
        return 400;
    }

    /**
     * @return array<int, string>
     */
    public function headers(): array
    {
        return ['Content-Type: text/html'];
    }
}
