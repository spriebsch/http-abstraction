<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UrlPath::class)]
final class UrlPathTest extends TestCase
{
    #[Test]
    public function adds_leading_slash_when_missing(): void
    {
        $path = new UrlPath('foo');

        self::assertSame('/foo', $path->asString());
    }

    #[Test]
    public function removes_query_string(): void
    {
        $path = new UrlPath('/foo?bar=baz');

        self::assertSame('/foo', $path->asString());
    }

    #[Test]
    public function removes_trailing_slash(): void
    {
        $path = new UrlPath('/foo/');

        self::assertSame('/foo', $path->asString());
    }

    #[Test]
    public function keeps_root_path_as_single_slash(): void
    {
        $path = new UrlPath('/');

        self::assertSame('/', $path->asString());
    }

    #[Test]
    public function preserves_already_normalized_path(): void
    {
        $path = new UrlPath('/foo');

        self::assertSame('/foo', $path->asString());
    }

    #[Test]
    public function throws_on_double_dots_in_path(): void
    {
        $this->expectException(HttpException::class);

        new UrlPath('/../etc/passwd');
    }

    #[Test]
    public function throws_on_percent_encoded_double_dots_in_path(): void
    {
        $this->expectException(HttpException::class);

        new UrlPath('/%2e%2e/secret');
    }
}
