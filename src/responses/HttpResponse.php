<?php declare(strict_types=1);

namespace spriebsch\http;

interface HttpResponse
{
    public function send(): void;

    /**
     * @return array<int, string>
     */
    public function headers(): array;

    public function content(): string;
}
