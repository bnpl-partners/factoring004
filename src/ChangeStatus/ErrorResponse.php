<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use BnplPartners\Factoring004\ArrayInterface;
use JsonSerializable;

/**
 * @psalm-immutable
 */
class ErrorResponse implements JsonSerializable, ArrayInterface
{
    private string $code;
    private string $error;
    private string $message;

    public function __construct(string $code, string $error, string $message)
    {
        $this->code = $code;
        $this->error = $error;
        $this->message = $message;
    }

    /**
     * @param array<string, string> $response
     * @psalm-param array{code: string, error: string, message: string} $response
     */
    public static function createFromArray(array $response): ErrorResponse
    {
        return new self($response['code'], $response['error'], $response['message']);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @psalm-return array{code: string, error: string, message: string}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'error' => $this->getError(),
            'message' => $this->getMessage(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
