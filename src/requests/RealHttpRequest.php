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

        $requestMethod = RequestMethod::tryFrom($_SERVER[$methodKey]);

        if ($requestMethod === null) {
            throw HttpException::unsupportedRequestMethod($_SERVER[$methodKey]);
        }

        return new self(
            $requestMethod,
            new UrlPath($_SERVER[$pathKey]),
            $_GET,
            $_POST,
            file_get_contents('php://input'),
        );
    }
}
