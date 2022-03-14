<?php

namespace BnplPartners\Factoring004\Otp;

use BnplPartners\Factoring004\ArrayInterface;

/**
 * @psalm-immutable
 */
class SendOtp implements ArrayInterface
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
     * @param string $merchantId
     * @param string $merchantOrderId
     */
    public function __construct($merchantId, $merchantOrderId)
    {
        $merchantId = (string) $merchantId;
        $merchantOrderId = (string) $merchantOrderId;
        $this->merchantId = $merchantId;
        $this->merchantOrderId = $merchantOrderId;
    }

    /**
     * @param array<string, mixed> $sendOtp
     *
     * @psalm-param array{merchantId: string, merchantOrderId: string} $sendOtp
     * @return \BnplPartners\Factoring004\Otp\SendOtp
     */
    public static function createFromArray($sendOtp)
    {
        return new self($sendOtp['merchantId'], $sendOtp['merchantOrderId']);
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
     * @psalm-return array{merchantId: string, merchantOrderId: string}
     * @return mixed[]
     */
    public function toArray()
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'merchantOrderId' => $this->getMerchantOrderId(),
        ];
    }
}
