<?php declare(strict_types=1);

namespace spriebsch\http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FakeHttpRequest::class)]
#[CoversClass(HttpException::class)]
#[UsesClass(UrlPath::class)]
final class FakeHttpRequestTest extends TestCase
{
    public function test_is_get_request(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertTrue($request->isGet());
    }

    public function test_is_not_post_request(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertFalse($request->isPost());
    }

    public function test_has_path(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertSame('/foo', $request->path()->asString());
    }

    public function test_has_parameter(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertTrue($request->hasParameter('id'));
    }

    public function test_parameter_as_string(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertSame('42', $request->parameterAsString('id'));
    }

    public function test_parameter_as_int(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertSame(42, $request->parameterAsInt('id'));
    }

    public function test_parameter_as_float(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertSame(42.0, $request->parameterAsFloat('id'));
    }

    public function test_does_not_have_missing_parameter(): void
    {
        $request = FakeHttpRequest::get('/foo', ['id' => '42', 'q' => 'x']);

        $this->assertFalse($request->hasParameter('missing'));
    }

    public function test_head_request_is_head(): void
    {
        $request = FakeHttpRequest::head('/status');

        $this->assertTrue($request->isHead());
    }

    public function test_head_request_is_considered_get(): void
    {
        $request = FakeHttpRequest::head('/status');

        $this->assertTrue($request->isGet());
    }

    public function test_head_request_is_not_post(): void
    {
        $request = FakeHttpRequest::head('/status');

        $this->assertFalse($request->isPost());
    }

    public function test_post_without_body_is_post(): void
    {
        $request = FakeHttpRequest::post('/submit');

        $this->assertTrue($request->isPost());
    }

    public function test_post_without_body_has_empty_body(): void
    {
        $request = FakeHttpRequest::post('/submit');

        $this->assertSame('', $request->body());
    }

    public function test_post_with_body_is_post(): void
    {
        $request = FakeHttpRequest::postWithBody('/submit', 'payload');

        $this->assertTrue($request->isPost());
    }

    public function test_post_with_body_has_body(): void
    {
        $request = FakeHttpRequest::postWithBody('/submit', 'payload');

        $this->assertSame('payload', $request->body());
    }

    public function test_post_with_body_has_empty_form_data(): void
    {
        $request = FakeHttpRequest::postWithBody('/submit', 'payload');

        $this->assertSame([], $request->formData());
    }

    public function test_post_with_form_data_is_post(): void
    {
        $request = FakeHttpRequest::postWithFormData('/submit', ['token' => 'abc', 'count' => 3]);

        $this->assertTrue($request->isPost());
    }

    public function test_post_with_form_data_has_form_data(): void
    {
        $request = FakeHttpRequest::postWithFormData('/submit', ['token' => 'abc', 'count' => 3]);

        $this->assertSame(['token' => 'abc', 'count' => 3], $request->formData());
    }

    public function test_post_with_form_data_has_empty_body(): void
    {
        $request = FakeHttpRequest::postWithFormData('/submit', ['token' => 'abc', 'count' => 3]);

        $this->assertSame('', $request->body());
    }

    public function test_from_creates_post_request(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertTrue($request->isPost());
    }

    public function test_from_sets_path(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertSame('/save', $request->path()->asString());
    }

    public function test_from_sets_parameters(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertTrue($request->hasParameter('a'));
    }

    public function test_from_sets_parameter_value(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertSame(1, $request->parameterAsInt('a'));
    }

    public function test_from_sets_form_data(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

        $this->assertSame(['a' => 1, 'b' => 2], $request->formData());
    }

    public function test_from_sets_body(): void
    {
        $request = FakeHttpRequest::from(RequestMethod::POST, new UrlPath('/save'), ['a' => 1, 'b' => 2], 'x');

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

    public function test_parameter_as_bool_uses_php_truthiness_for_true(): void
    {
        $request = FakeHttpRequest::get('/flags', ['on' => 1, 'off' => 0]);

        $this->assertTrue($request->parameterAsBool('on'));
    }

    public function test_parameter_as_bool_uses_php_truthiness_for_false(): void
    {
        $request = FakeHttpRequest::get('/flags', ['on' => 1, 'off' => 0]);

        $this->assertFalse($request->parameterAsBool('off'));
    }

    public function test_throws_when_parameter_not_found(): void
    {
        $this->expectException(\RuntimeException::class);

        $request = FakeHttpRequest::get('/x');
        $request->parameterAsString('missing');
    }

    public function test_throws_when_parameter_is_not_a_string(): void
    {
        $this->expectException(\RuntimeException::class);

        $request = FakeHttpRequest::get('/x', ['key' => []]);
        $request->parameterAsString('key');
    }

    public function test_throws_when_parameter_is_not_an_int(): void
    {
        $this->expectException(\RuntimeException::class);

        $request = FakeHttpRequest::get('/x', ['key' => 'not-a-number']);
        $request->parameterAsInt('key');
    }

    public function test_throws_when_parameter_is_not_a_float(): void
    {
        $this->expectException(\RuntimeException::class);

        $request = FakeHttpRequest::get('/x', ['key' => 'not-a-number']);
        $request->parameterAsFloat('key');
    }
}
