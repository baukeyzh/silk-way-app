<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class FirebaseServiceException extends RuntimeException
{
    public function __construct(
        string $message = 'Firebase service error',
        private readonly string $firebaseErrorCode = '',
    ) {
        parent::__construct($message);
    }

    public function getFirebaseErrorCode(): string
    {
        return $this->firebaseErrorCode;
    }
}
