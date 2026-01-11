<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaHttpResponse::class)]
final class MediaHttpResponseTest extends TestCase
{
    public function test_url_is_returned(): void
    {
        $url = '/media/file.jpg';
        $response = new MediaHttpResponse($url);
        $this->assertSame($url, $response->url());
    }

    public function test_response_code_is_200(): void
    {
        $response = new MediaHttpResponse('/media/file.jpg');
        $this->assertSame(200, $response->responseCode());
    }

    public function test_headers_are_correct(): void
    {
        $url = '/media/file.jpg';
        $response = new MediaHttpResponse($url);
        $expected = [
            'Content-Type:',
            'X-Accel-Redirect: ' . $url,
        ];
        $this->assertSame($expected, $response->headers());
    }

    public function test_body_is_empty(): void
    {
        $response = new MediaHttpResponse('/media/file.jpg');
        $this->assertSame('', $response->content());
    }
}
