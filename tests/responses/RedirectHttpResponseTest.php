<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TemporaryRedirectHttpResponse::class)]
#[CoversClass(PermanentRedirectHttpResponse::class)]
#[CoversClass(RedirectHttpResponse::class)]
final class RedirectHttpResponseTest extends TestCase
{
    #[Test]
    public function temporary_redirect_sets_location_header_and_status_code(): void
    {
        $response = new TemporaryRedirectHttpResponse('target');

        self::assertSame(['Location: /target'], $response->headers());
        self::assertSame(302, $response->responseCode());
        self::assertSame('', $response->content());
    }

    #[Test]
    public function permanent_redirect_sets_location_header_and_status_code(): void
    {
        $response = new PermanentRedirectHttpResponse('elsewhere');

        self::assertSame(['Location: /elsewhere'], $response->headers());
        self::assertSame(301, $response->responseCode());
        self::assertSame('', $response->content());
    }
}
