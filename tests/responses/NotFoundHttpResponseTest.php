<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NotFoundHttpResponse::class)]
final class NotFoundHttpResponseTest extends TestCase
{
    public function test_has_not_found_status_code_and_no_headers(): void
    {
        $response = new NotFoundHttpResponse('missing');

        $this->assertSame(404, $response->responseCode());
        $this->assertSame([], $response->headers());
    }

    public function test_returns_provided_content(): void
    {
        $response = new NotFoundHttpResponse('not found');

        $this->assertSame('not found', $response->content());
    }
}
