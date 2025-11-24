<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(BadRequestHttpResponse::class)]
final class BadRequestHttpResponseTest extends TestCase
{
    #[Test]
    public function has_bad_request_status_code(): void
    {
        $response = new BadRequestHttpResponse();

        self::assertSame(400, $response->responseCode());
    }

    #[Test]
    public function sets_html_content_type_header(): void
    {
        $response = new BadRequestHttpResponse();

        self::assertSame(['Content-Type: text/html'], $response->headers());
    }

    #[Test]
    public function has_default_content_and_send_prints_it(): void
    {
        $response = new BadRequestHttpResponse();

        self::assertSame('Bad Request', $response->content());

        ob_start();
        $response->send();
        $output = ob_get_clean();

        self::assertSame('Bad Request', $output);
    }
}
