<?php

declare(strict_types=1);

namespace App\Shared\Enum;

enum HttpMethodEnum: string implements TransformableToString
{
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';

    public function toString(): string
    {
        return match ($this) {
          self::GET  => self::GET->value,
          self::HEAD  => self::HEAD->value,
          self::POST  => self::POST->value,
          self::PUT  => self::PUT->value,
          self::DELETE  => self::DELETE->value,
          self::PATCH  => self::PATCH->value,
        };
    }
}