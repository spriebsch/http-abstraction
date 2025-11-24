<?php declare(strict_types=1);

namespace spriebsch\http;

final class PermanentRedirectHttpResponse extends RedirectHttpResponse
{
    public function responseCode(): int
    {
        return 301;
    }
}
