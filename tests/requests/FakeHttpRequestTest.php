<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FakeHttpRequest::class)]
final class FakeHttpRequestTest extends TestCase
{
    public function test_creates_get_request_with_parameters(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());
        $this->assertSame('/foo', $request->path()->asString());
        $this->assertTrue($request->hasParameter('id'));
        $this->assertSame('42', $request->parameterAsString('id'));
        $this->assertSame(42, $request->parameterAsInt('id'));
        $this->assertSame(42.0, $request->parameterAsFloat('id'));
        $this->assertFalse($request->hasParameter('missing'));
    }

    public function test_head_request_is_head_and_considered_get(): void
    {
        $request = FakeHttpRequest::head('/status');

        $this->assertTrue($request->isHead());
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());
    }

    public function test_post_without_body_returns_empty_body(): void
    {
        $request = FakeHttpRequest::post('/submit');

        $this->assertTrue($request->isPost());
        $this->assertSame('', $request->body());
    }

    public function test_post_with_body_returns_body(): void
    {
        $request = FakeHttpRequest::postWithBody('/submit', 'payload');

        $this->assertTrue($request->isPost());
        $this->assertSame('payload', $request->body());
        $this->assertSame([], $request->formData());
    }

    public function test_post_with_form_data_sets_form_data_and_has_empty_body(): void
    {
        $request = FakeHttpRequest::postWithFormData('/submit', ['token' => 'abc', 'count' => 3]);

        $this->assertTrue($request->isPost());
        $this->assertSame(['token' => 'abc', 'count' => 3], $request->formData());
        $this->assertSame('', $request->body());
    }

    public function test_from_creates_post_request_with_parameters_as_form_data(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertTrue($request->isPost());
        $this->assertSame('/save', $request->path()->asString());
        // Parameters are available both as URL parameters and form data
        $this->assertTrue($request->hasParameter('a'));
        $this->assertSame(1, $request->parameterAsInt('a'));
        $this->assertSame(['a' => 1, 'b' => 2], $request->formData());
        $this->assertSame('x', $request->body());
    }

    public function test_from_throws_when_non_post_request_has_body(): void
    {
        $this->expectException(HttpException::class);

        // GET must not have a body
        FakeHttpRequest::from(RequestMethod::GET, new UrlPath('/x'), [], 'nope');
    }

    public function test_body_is_not_available_on_get_or_head(): void
    {
        $this->expectException(HttpException::class);

        $request = FakeHttpRequest::get('/x');
        // Should throw because non-POST requests do not have a body
        $request->body();
    }

    public function test_parameter_as_bool_uses_php_truthiness(): void
    {
        $request = FakeHttpRequest::get('/flags', ['on' => 1, 'off' => 0]);

        $this->assertTrue($request->parameterAsBool('on'));
        $this->assertFalse($request->parameterAsBool('off'));
    }
}
