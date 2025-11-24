<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BadRequestHttpResponse::class)]
final class BadRequestHttpResponseTest extends TestCase
{
    public function test_has_bad_request_status_code(): void
    {
        $response = new BadRequestHttpResponse();

        $this->assertSame(400, $response->responseCode());
    }

    public function test_sets_html_content_type_header(): void
    {
        $response = new BadRequestHttpResponse();

        $this->assertSame(['Content-Type: text/html'], $response->headers());
    }

    public function test_has_default_content_and_send_prints_it(): void
    {
        $response = new BadRequestHttpResponse();

        $this->assertSame('Bad Request', $response->content());

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertSame('Bad Request', $output);
    }
}
