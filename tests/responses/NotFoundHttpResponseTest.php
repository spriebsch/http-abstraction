<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(NotFoundHttpResponse::class)]
final class NotFoundHttpResponseTest extends TestCase
{
    #[Test]
    public function has_not_found_status_code_and_no_headers(): void
    {
        $response = new NotFoundHttpResponse('missing');

        self::assertSame(404, $response->responseCode());
        self::assertSame([], $response->headers());
    }

    #[Test]
    public function returns_provided_content(): void
    {
        $response = new NotFoundHttpResponse('not found');

        self::assertSame('not found', $response->content());
    }
}
