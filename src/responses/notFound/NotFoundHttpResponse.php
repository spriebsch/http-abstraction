<?php declare(strict_types=1);

namespace spriebsch\http;

final class NotFoundHttpResponse extends AbstractHttpResponse
{
    public function responseCode(): int
    {
        return 404;
    }

    public function headers(): array
    {
        return [];
    }
}
