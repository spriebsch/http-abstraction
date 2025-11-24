<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(HtmlContentHttpResponse::class)]
final class HtmlContentHttpResponseTest extends TestCase
{
    #[Test]
    public function reports_html_content_type_header(): void
    {
        $response = new HtmlContentHttpResponse('<h1>Hi</h1>');

        self::assertSame(['Content-Type: text/html'], $response->headers());
    }

    #[Test]
    public function has_ok_status_code(): void
    {
        $response = new HtmlContentHttpResponse('ok');

        self::assertSame(200, $response->responseCode());
    }

    #[Test]
    public function returns_provided_content(): void
    {
        $response = new HtmlContentHttpResponse('content');

        self::assertSame('content', $response->content());
    }

    #[Test]
    public function send_prints_content(): void
    {
        $response = new HtmlContentHttpResponse('printed');

        ob_start();
        $response->send();
        $output = ob_get_clean();

        self::assertSame('printed', $output);
    }
}
