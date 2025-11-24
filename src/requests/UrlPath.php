<?php declare(strict_types=1);

namespace spriebsch\http;

class UrlPath
{
    public function __construct(private string $path)
    {
        if (!str_starts_with($this->path, '/')) {
            $this->path = '/' . $this->path;
        }

        if (str_contains($this->path, '?')) {
            $this->path = substr($this->path, 0, strpos($this->path, '?'));
        }

        if (str_ends_with($this->path, '/')) {
            $this->path = substr($this->path, 0, -1);
        }

        $this->ensurePathDoesNotContainDoubleDots($this->path);
    }

    private function ensurePathDoesNotContainDoubleDots(string $path): void
    {
        if (str_contains($path, '..')) {
            throw HttpException::urlPathMustNotContainDoubleDots($path);
        }

        if (str_contains($path, '%2e%2e')) {
            throw HttpException::urlPathMustNotContainDoubleDots($path);
        }
    }

    public function asString(): string
    {
        return $this->path;
    }
}
