<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\ArrayInterface;
use JsonSerializable;

class DtoOtp implements JsonSerializable, ArrayInterface
{
    private string $msg;

    public function __construct(string $msg)
    {
        $this->msg = $msg;
    }

    /**
     * @param array<string, string> $changeStatus
     * @psalm-param array{msg: string} $changeStatus
     */
    public static function createFromArray(array $changeStatus): DtoOtp
    {
        return new self($changeStatus['msg']);
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @psalm-return array{msg: string}
     */
    public function toArray(): array
    {
        return [
            'msg' => $this->getMsg(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
