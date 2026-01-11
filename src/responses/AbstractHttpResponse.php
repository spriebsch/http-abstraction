<?php declare(strict_types=1);

namespace spriebsch\http;

abstract class AbstractHttpResponse implements HttpResponse
{
    public function __construct(
        private readonly string $content,
    ) {}

    final public function send(): void
    {
        http_response_code($this->responseCode());

        foreach ($this->headers() as $header) {
            header($header);
        }

        print $this->content();
    }

    final public function content(): string
    {
        return $this->content;
    }

    abstract public function responseCode(): int;

    /**
     * @return array<int, string>
     */
    abstract public function headers(): array;
}
