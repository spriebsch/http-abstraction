<?php declare(strict_types=1);

namespace spriebsch\http;

final readonly class RealHttpRequest extends AbstractHttpRequest implements HttpRequest
{
    public static function fromSuperglobals(): self
    {
        $methodKey = 'REQUEST_METHOD';
        $pathKey = 'REQUEST_URI';

        if (!isset($_SERVER[$methodKey])) {
            throw HttpException::serverVariableNotSet($methodKey);
        }

        if (!isset($_SERVER[$pathKey])) {
            throw HttpException::serverVariableNotSet($pathKey);
        }

        $method = $_SERVER[$methodKey];
        if (!is_string($method)) {
            throw HttpException::serverVariableNotSet($methodKey);
        }

        $requestMethod = RequestMethod::tryFrom($method);

        if ($requestMethod === null) {
            throw HttpException::unsupportedRequestMethod($method);
        }

        $path = $_SERVER[$pathKey];
        if (!is_string($path)) {
            throw HttpException::serverVariableNotSet($pathKey);
        }

        $body = file_get_contents('php://input');
        if ($body === false) {
            $body = '';
        }

        /** @var array<string, mixed> $get */
        $get = $_GET;
        /** @var array<string, mixed> $post */
        $post = $_POST;

        return new self(
            $requestMethod,
            new UrlPath($path),
            $get,
            $post,
            $body,
        );
    }
}
