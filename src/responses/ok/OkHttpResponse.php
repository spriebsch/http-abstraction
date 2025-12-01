<?php declare(strict_types=1);

namespace spriebsch\http;

final class OkHttpResponse extends AbstractHttpResponse
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function responseCode(): int
    {
        return 204;
    }

    public function headers(): array
    {
        return [];
    }
}
