<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
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

    public function test_creates_get_request_from_superglobals(): void
    {
        $this->setServer('GET', '/foo?x=1');
        $_GET = ['x' => '1'];
        $_POST = [];

        $request = RealHttpRequest::fromSuperglobals();

        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());
        $this->assertSame('/foo', $request->path()->asString());
        $this->assertTrue($request->hasParameter('x'));
        $this->assertSame('1', $request->parameterAsString('x'));
    }

    public function test_head_request_is_head_and_considered_get_from_superglobals(): void
    {
        $this->setServer('HEAD', '/status');
        $_GET = [];
        $_POST = [];

        $request = RealHttpRequest::fromSuperglobals();

        $this->assertTrue($request->isHead());
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());

        $this->expectException(HttpException::class);
        $request->body();
    }

    public function test_post_request_uses_post_data_and_body_is_empty_in_cli(): void
    {
        $this->setServer('POST', '/submit?track=1');
        $_GET = ['track' => '1'];
        $_POST = ['token' => 'abc', 'count' => 3];

        $request = RealHttpRequest::fromSuperglobals();

        $this->assertTrue($request->isPost());
        $this->assertSame(['token' => 'abc', 'count' => 3], $request->formData());
        // php://input is empty in our test environment
        $this->assertSame('', $request->body());
        // URL params are still available
        $this->assertTrue($request->hasParameter('track'));
        $this->assertSame(1, $request->parameterAsInt('track'));
    }

    public function test_throws_when_request_method_missing(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        $_SERVER['REQUEST_URI'] = '/x';

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }

    public function test_throws_when_request_uri_missing(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['REQUEST_URI']);

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }

    public function test_throws_on_unsupported_request_method(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'TRACE';
        $_SERVER['REQUEST_URI'] = '/';

        $this->expectException(HttpException::class);
        RealHttpRequest::fromSuperglobals();
    }
}
