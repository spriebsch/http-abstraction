<?php declare(strict_types=1);

namespace spriebsch\http;


abstract class
RedirectHttpResponse extends AbstractHttpResponse
{
    public function __construct(private readonly string $url)
    {
        parent::__construct('');
    }

    /**
     * @return array<int, string>
     */
    public function headers(): array
    {
        return [
            sprintf('Location: %s', $this->url),
        ];
    }

    abstract public function responseCode(): int;
}
