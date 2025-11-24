<?php declare(strict_types=1);

namespace spriebsch\http;


abstract class
RedirectHttpResponse extends AbstractHttpResponse
{
    public function __construct(private readonly string $path)
    {
        parent::__construct('');
    }

    public function headers(): array
    {
        return [
            sprintf('Location: /%s', $this->path),
        ];
    }

    abstract public function responseCode(): int;
}
