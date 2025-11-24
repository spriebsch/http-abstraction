<?php declare(strict_types=1);

namespace spriebsch\http;

abstract class ContentHttpResponse extends AbstractHttpResponse
{
    public function responseCode(): int
    {
        return 200;
    }
}
