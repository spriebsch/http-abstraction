<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UrlPath::class)]
#[CoversClass(HttpException::class)]
final class UrlPathTest extends TestCase
{
    public function test_adds_leading_slash_when_missing(): void
    {
        $path = new UrlPath('foo');

        $this->assertSame('/foo', $path->asString());
    }

    public function test_removes_query_string(): void
    {
        $path = new UrlPath('/foo?bar=baz');

        $this->assertSame('/foo', $path->asString());
    }

    public function test_removes_trailing_slash(): void
    {
        $path = new UrlPath('/foo/');

        $this->assertSame('/foo', $path->asString());
    }

    public function test_keeps_root_path_as_single_slash(): void
    {
        $path = new UrlPath('/');

        $this->assertSame('/', $path->asString());
    }

    public function test_preserves_already_normalized_path(): void
    {
        $path = new UrlPath('/foo');

        $this->assertSame('/foo', $path->asString());
    }

    public function test_throws_on_double_dots_in_path(): void
    {
        $this->expectException(HttpException::class);

        new UrlPath('/../etc/passwd');
    }

    public function test_throws_on_percent_encoded_double_dots_in_path(): void
    {
        $this->expectException(HttpException::class);

        new UrlPath('/%2e%2e/secret');
    }
}
