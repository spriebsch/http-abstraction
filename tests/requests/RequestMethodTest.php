<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestMethod::class)]
final class RequestMethodTest extends TestCase
{
    public function test_cases(): void
    {
        $this->assertSame('HEAD', RequestMethod::HEAD->value);
        $this->assertSame('GET', RequestMethod::GET->value);
        $this->assertSame('POST', RequestMethod::POST->value);
    }
}
