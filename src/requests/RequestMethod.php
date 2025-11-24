<?php declare(strict_types=1);

namespace spriebsch\http;

enum RequestMethod: string
{
    case HEAD = 'HEAD';
    case GET = 'GET';
    case POST = 'POST';
}
