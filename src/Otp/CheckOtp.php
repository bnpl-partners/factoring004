<?php

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\ArrayInterface;

/**
 * @psalm-immutable
 */
class CheckOtp implements ArrayInterface
{
    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $merchantOrderId;
    /**
     * @var string
     */
    private $otp;

    /**
     * @param string $merchantId
     * @param string $merchantOrderId
     * @param string $otp
     */
    public function __construct($merchantId, $merchantOrderId, $otp)
    {
        $merchantId = (string) $merchantId;
        $merchantOrderId = (string) $merchantOrderId;
        $otp = (string) $otp;
        $this->merchantId = $merchantId;
        $this->merchantOrderId = $merchantOrderId;
        $this->otp = $otp;
    }

    /**
     * @param array<string, mixed> $checkOtp
     * @psalm-param array{merchantId: string, merchantOrderId: string, otp: string} $checkOtp
     * @return \BnplPartners\Factoring004\Otp\CheckOtp
     */
    public static function createFromArray($checkOtp)
    {
        return new self($checkOtp['merchantId'], $checkOtp['merchantOrderId'], $checkOtp['otp']);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return string
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * @return string
     */
    public function getOtp()
    {
        return $this->otp;
    }

    /**
     * @psalm-return array{merchantId: string, merchantOrderId: string, otp: string}
     * @return mixed[]
     */
    public function toArray()
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'merchantOrderId' => $this->getMerchantOrderId(),
            'otp' => $this->getOtp(),
        ];
    }
}
