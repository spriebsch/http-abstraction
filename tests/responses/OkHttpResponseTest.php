<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OkHttpResponse::class)]
final class OkHttpResponseTest extends TestCase
{
    public function test_response_code_is_204(): void
    {
        $response = new OkHttpResponse();
        $this->assertSame(204, $response->responseCode());
    }

    public function test_headers_are_empty(): void
    {
        $response = new OkHttpResponse();
        $this->assertSame([], $response->headers());
    }

    public function test_body_is_empty(): void
    {
        $response = new OkHttpResponse();
        $this->assertSame('', $response->content());
    }
}
