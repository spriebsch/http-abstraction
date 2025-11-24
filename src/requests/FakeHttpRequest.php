<?php declare(strict_types=1);

namespace spriebsch\http;

final readonly class FakeHttpRequest extends AbstractHttpRequest implements HttpRequest
{
    public static function get(string $path, array $parameters = []): self
    {
        return new self(RequestMethod::GET, new UrlPath($path), $parameters);
    }

    public static function head(string $path, array $parameters = []): self
    {
        return new self(RequestMethod::HEAD, new UrlPath($path), $parameters);
    }

    public static function post(string $path): self
    {
        return new self(RequestMethod::POST, new UrlPath($path));
    }

    public static function postWithBody(string $path, string $body = ''): self
    {
        return new self(RequestMethod::POST, new UrlPath($path), [], [], $body);
    }

    public static function postWithFormData(string $path, array $formData = []): self
    {
        return new self(RequestMethod::POST, new UrlPath($path), [], $formData);
    }

    public static function from(RequestMethod $method, UrlPath $path, array $parameters = [], string $body = ''): self
    {
        return new self($method, $path, $parameters, $parameters, $body);
    }
}
