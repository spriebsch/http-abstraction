<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RealHttpRequest::class)]
final class RealHttpRequestTest extends TestCase
{
    private array $serverBackup = [];
    private array $getBackup = [];
    private array $postBackup = [];

    protected function setUp(): void
    {
        $this->serverBackup = $_SERVER;
        $this->getBackup = $_GET;
        $this->postBackup = $_POST;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serverBackup;
        $_GET = $this->getBackup;
        $_POST = $this->postBackup;
    }

    private function setServer(string $method, string $uri): void
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
    }

    #[Test]
    public function it_creates_get_request_from_superglobals(): void
    {
        $this->setServer('GET', '/foo?x=1');
        $_GET = ['x' => '1'];
        $_POST = [];

        $request = RealHttpRequest::fromSuperglobals();

        self::assertTrue($request->isGet());
        self::assertFalse($request->isPost());
        self::assertSame('/foo', $request->path()->asString());
        self::assertTrue($request->hasParameter('x'));
        self::assertSame('1', $request->parameterAsString('x'));
    }

    #[Test]
    public function head_request_is_head_and_considered_get_from_superglobals(): void
    {
        $this->setServer('HEAD', '/status');
        $_GET = [];
        $_POST = [];

        $request = RealHttpRequest::fromSuperglobals();

        self::assertTrue($request->isHead());
        self::assertTrue($request->isGet());
        self::assertFalse($request->isPost());

        $this->expectException(HttpException::class);
        $request->body();
    }

    #[Test]
    public function post_request_uses_post_data_and_body_is_empty_in_cli(): void
    {
        $this->setServer('POST', '/submit?track=1');
        $_GET = ['track' => '1'];
        $_POST = ['token' => 'abc', 'count' => 3];

        $request = RealHttpRequest::fromSuperglobals();

        self::assertTrue($request->isPost());
        self::assertSame(['token' => 'abc', 'count' => 3], $request->formData());
        // php://input is empty in our test environment
        self::assertSame('', $request->body());
        // URL params are still available
        self::assertTrue($request->hasParameter('track'));
        self::assertSame(1, $request->parameterAsInt('track'));
    }

    #[Test]
    public function it_throws_when_request_method_missing(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        $_SERVER['REQUEST_URI'] = '/x';

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }

    #[Test]
    public function it_throws_when_request_uri_missing(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['REQUEST_URI']);

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }

    #[Test]
    public function it_throws_on_unsupported_request_method(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'TRACE';
        $_SERVER['REQUEST_URI'] = '/';

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }
}
