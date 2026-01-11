<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestMethod::class)]
final class RequestMethodTest extends TestCase
{
    public function test_head_case(): void
    {
        $this->assertSame('HEAD', RequestMethod::HEAD->value);
    }

    public function test_get_case(): void
    {
        $this->assertSame('GET', RequestMethod::GET->value);
    }

    public function test_post_case(): void
    {
        $this->assertSame('POST', RequestMethod::POST->value);
    }
}
