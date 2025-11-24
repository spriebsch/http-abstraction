<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TemporaryRedirectHttpResponse::class)]
#[CoversClass(PermanentRedirectHttpResponse::class)]
#[CoversClass(RedirectHttpResponse::class)]
final class RedirectHttpResponseTest extends TestCase
{
    public function test_temporary_redirect_sets_location_header_and_status_code(): void
    {
        $response = new TemporaryRedirectHttpResponse('target');

        $this->assertSame(['Location: /target'], $response->headers());
        $this->assertSame(302, $response->responseCode());
        $this->assertSame('', $response->content());
    }

    public function test_permanent_redirect_sets_location_header_and_status_code(): void
    {
        $response = new PermanentRedirectHttpResponse('elsewhere');

        $this->assertSame(['Location: /elsewhere'], $response->headers());
        $this->assertSame(301, $response->responseCode());
        $this->assertSame('', $response->content());
    }
}
