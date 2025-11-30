<?php declare(strict_types=1);

namespace spriebsch\http;

final class MediaHttpResponse extends AbstractHttpResponse
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;

        parent::__construct('');
    }

    public function url(): string
    {
        return $this->url;
    }

    public function responseCode(): int
    {
        return 200;
    }

    public function headers(): array
    {
        return [
            'Content-Type:', // Makes nginx determine the MIME type
            'X-Accel-Redirect: ' . str_replace(
                '/media/',
                '/protected/',
                $this->url,
            ),
        ];
    }
}
