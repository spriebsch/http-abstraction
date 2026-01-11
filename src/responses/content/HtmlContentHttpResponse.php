<?php declare(strict_types=1);

namespace spriebsch\http;

final class HtmlContentHttpResponse extends ContentHttpResponse
{
    /**
     * @return array<int, string>
     */
    public function headers(): array
    {
        return ['Content-Type: text/html'];
    }
}
