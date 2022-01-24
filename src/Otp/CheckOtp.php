<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\ArrayInterface;

/**
 * @psalm-immutable
 */
class CheckOtp implements ArrayInterface
{
    private string $merchantId;
    private string $merchantOrderId;
    private string $otp;

    public function __construct(string $merchantId, string $merchantOrderId, string $otp)
    {
        $this->merchantId = $merchantId;
        $this->merchantOrderId = $merchantOrderId;
        $this->otp = $otp;
    }

    /**
     * @param array<string, mixed> $checkOtp
     * @psalm-param array{merchantId: string, merchantOrderId: string, otp: string} $checkOtp
     */
    public static function createFromArray(array $checkOtp): CheckOtp
    {
        return new self($checkOtp['merchantId'], $checkOtp['merchantOrderId'], $checkOtp['otp']);
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getMerchantOrderId(): string
    {
        return $this->merchantOrderId;
    }

    public function getOtp(): string
    {
        return $this->otp;
    }

    /**
     * @psalm-return array{merchantId: string, merchantOrderId: string, otp: string}
     */
    public function toArray(): array
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'merchantOrderId' => $this->getMerchantOrderId(),
            'otp' => $this->getOtp(),
        ];
    }
}
