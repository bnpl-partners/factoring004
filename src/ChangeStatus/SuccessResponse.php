<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use BnplPartners\Factoring004\ArrayInterface;
use JsonSerializable;

/**
 * @psalm-immutable
 */
class SuccessResponse implements JsonSerializable, ArrayInterface
{
    private string $error;
    private string $msg;

    public function __construct(string $error, string $msg)
    {
        $this->error = $error;
        $this->msg = $msg;
    }

    /**
     * @param array<string, string> $response
     * @psalm-param array{error: string, msg: string} $response
     *
     * @return \BnplPartners\Factoring004\ChangeStatus\SuccessResponse
     */
    public static function createFromArray(array $response): SuccessResponse
    {
        return new self($response['error'], $response['msg']);
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @psalm-return array{error: string, msg: string}
     */
    public function toArray(): array
    {
        return [
            'error' => $this->getError(),
            'msg' => $this->getMsg(),
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
