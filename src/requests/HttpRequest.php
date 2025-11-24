<?php declare(strict_types=1);

namespace spriebsch\http;

interface HttpRequest
{
    public function isGet(): bool;

    public function isHead(): bool;

    public function isPost(): bool;

    public function path(): UrlPath;

    public function formData(): array;

    public function body(): string;

    public function hasParameter(string $name): bool;

    public function parameterAsString(string $name): string;

    public function parameterAsInt(string $name): int;

    public function parameterAsFloat(string $name): float;

    public function parameterAsBool(string $name): bool;
}
