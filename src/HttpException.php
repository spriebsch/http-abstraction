<?php declare(strict_types=1);

namespace spriebsch\http;

use RuntimeException;

final class HttpException extends RuntimeException
{
    public static function unsupportedRequestMethod(string $method): self
    {
        return new self(sprintf('Unsupported HTTP request method "%s"', $method));
    }

    public static function cannotHavePostData(RequestMethod $method): self
    {
        return new self(sprintf('HTTP method "%s" cannot have POST data', $method->name));
    }

    public static function urlPathMustNotContainDoubleDots(string $path): self
    {
        return new self(sprintf('URL path "%s" must not contain double dots', $path));
    }

    public static function doesNotHaveBody(RequestMethod $method): self
    {
        return new self(sprintf('HTTP "%s" request has no form data', $method->name));
    }

    public static function serverVariableNotSet(string $name): self
    {
        return new self(sprintf('Server variable "%s" not set', $name));
    }
}
