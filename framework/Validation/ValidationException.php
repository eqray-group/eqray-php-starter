<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Validation;

class ValidationException extends \RuntimeException
{
    public function __construct(
        public mixed $errors,
        string $message = '校验失败',
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }
}
