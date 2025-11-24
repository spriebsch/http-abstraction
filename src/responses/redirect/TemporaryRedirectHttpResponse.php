<?php declare(strict_types=1);

namespace spriebsch\http;

final class
TemporaryRedirectHttpResponse extends RedirectHttpResponse
{
    public function responseCode(): int
    {
        return 302;
    }
}
