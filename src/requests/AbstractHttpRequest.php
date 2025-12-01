<?php declare(strict_types=1);

namespace spriebsch\http;

use RuntimeException;

abstract readonly class AbstractHttpRequest implements HttpRequest
{
    protected function __construct(
        private RequestMethod $method,
        private UrlPath       $path,
        private array         $urlParameters = [],
        private array         $formData = [],
        private string        $body = ''
    )
    {
        $this->ensureOnlyPostRequestHasBody($method, $body);
    }

    private function ensureOnlyPostRequestHasBody(RequestMethod $method, string $body)
    {
        if ($method !== RequestMethod::POST && $body !== '') {
            throw HttpException::cannotHavePostData($method);
        }
    }

    public function isGet(): bool
    {
        return $this->method === RequestMethod::GET || $this->isHead();
    }

    public function isHead(): bool
    {
        return $this->method === RequestMethod::HEAD;
    }

    public function isPost(): bool
    {
        return $this->method === RequestMethod::POST;
    }

    protected function isNoPost(): bool
    {
        return !$this->isPost();
    }

    public function path(): UrlPath
    {
        return $this->path;
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->urlParameters[$name]) || isset($this->formData[$name]);
    }

    public function parameterAsString(string $name): string
    {
        return (string) $this->data($name);
    }

    public function parameterAsInt(string $name): int
    {
        return (int) $this->data($name);
    }

    public function parameterAsFloat(string $name): float
    {
        return (int) $this->data($name);
    }

    public function parameterAsBool(string $name): bool
    {
        return (bool) $this->data($name);
    }

    public function body(): string
    {
        if ($this->isNoPost()) {
            throw HttpException::doesNotHaveBody($this->method);
        }

        return $this->body;
    }

    public function formData(): array
    {
        return $this->formData;
    }

    private function data(string $key): mixed
    {
        if (isset($this->formData[$key])) {
            return $this->formData[$key];
        }

        if (isset($this->urlParameters[$key])) {
            return $this->urlParameters[$key];
        }

        throw new RuntimeException(sprintf('Parameter "%s" not found.', $key));
    }
}
