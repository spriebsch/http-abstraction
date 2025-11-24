<?php declare(strict_types=1);

namespace spriebsch\http;

final class HtmlContentHttpResponse extends ContentHttpResponse
{
    public function headers(): array
    {
        return ['Content-Type: text/html'];
    }
}
