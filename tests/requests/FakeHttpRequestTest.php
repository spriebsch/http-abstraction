<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FakeHttpRequest::class)]
final class FakeHttpRequestTest extends TestCase
{
    #[Test]
    public function creates_get_request_with_parameters(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        self::assertTrue($request->isGet());
        self::assertFalse($request->isPost());
        self::assertSame('/foo', $request->path()->asString());
        self::assertTrue($request->hasParameter('id'));
        self::assertSame('42', $request->parameterAsString('id'));
        self::assertSame(42, $request->parameterAsInt('id'));
        self::assertSame(42.0, $request->parameterAsFloat('id'));
        self::assertFalse($request->hasParameter('missing'));
    }

    #[Test]
    public function head_request_is_head_and_considered_get(): void
    {
        $request = FakeHttpRequest::head('/status');

        self::assertTrue($request->isHead());
        self::assertTrue($request->isGet());
        self::assertFalse($request->isPost());
    }

    #[Test]
    public function post_without_body_returns_empty_body(): void
    {
        $request = FakeHttpRequest::post('/submit');

        self::assertTrue($request->isPost());
        self::assertSame('', $request->body());
    }

    #[Test]
    public function post_with_body_returns_body(): void
    {
        $request = FakeHttpRequest::postWithBody('/submit', 'payload');

        self::assertTrue($request->isPost());
        self::assertSame('payload', $request->body());
        self::assertSame([], $request->formData());
    }

    #[Test]
    public function post_with_form_data_sets_form_data_and_has_empty_body(): void
    {
        $request = FakeHttpRequest::postWithFormData('/submit', ['token' => 'abc', 'count' => 3]);

        self::assertTrue($request->isPost());
        self::assertSame(['token' => 'abc', 'count' => 3], $request->formData());
        self::assertSame('', $request->body());
    }

    #[Test]
    public function from_creates_post_request_with_parameters_as_form_data(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        self::assertTrue($request->isPost());
        self::assertSame('/save', $request->path()->asString());
        // Parameters are available both as URL parameters and form data
        self::assertTrue($request->hasParameter('a'));
        self::assertSame(1, $request->parameterAsInt('a'));
        self::assertSame(['a' => 1, 'b' => 2], $request->formData());
        self::assertSame('x', $request->body());
    }

    #[Test]
    public function from_throws_when_non_post_request_has_body(): void
    {
        $this->expectException(HttpException::class);

        // GET must not have a body
        FakeHttpRequest::from(RequestMethod::GET, new UrlPath('/x'), [], 'nope');
    }

    #[Test]
    public function body_is_not_available_on_get_or_head(): void
    {
        $this->expectException(HttpException::class);

        $request = FakeHttpRequest::get('/x');
        // Should throw because non-POST requests do not have a body
        $request->body();
    }

    #[Test]
    public function parameter_as_bool_uses_php_truthiness(): void
    {
        $request = FakeHttpRequest::get('/flags', ['on' => 1, 'off' => 0]);

        self::assertTrue($request->parameterAsBool('on'));
        self::assertFalse($request->parameterAsBool('off'));
    }
}
