<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class WhatsAppServiceException extends RuntimeException
{
    public function __construct(
        string $message = 'WhatsApp service error',
        private readonly int $wahaStatusCode = 0,
        private readonly string $wahaResponseBody = '',
    ) {
        parent::__construct($message);
    }

    public function getWahaStatusCode(): int
    {
        return $this->wahaStatusCode;
    }

    public function getWahaResponseBody(): string
    {
        return $this->wahaResponseBody;
    }
}
