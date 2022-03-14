<?php

namespace BnplPartners\Factoring004\ChangeStatus;

use BnplPartners\Factoring004\ArrayInterface;

class MerchantsOrders implements ArrayInterface
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder[]
     */
    private $orders;

    /**
     * @param \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder[] $orders
     * @param string $merchantId
     */
    public function __construct($merchantId, array $orders)
    {
        $merchantId = (string) $merchantId;
        $this->merchantId = $merchantId;
        $this->orders = $orders;
    }

    /**
     * @param array<string, mixed> $merchantsOrders
     * @psalm-param array{merchantId: string, orders: array{orderId: string, status: string, amount?: int}[]} $merchantsOrders
     * @return \BnplPartners\Factoring004\ChangeStatus\MerchantsOrders
     */
    public static function createFromArray($merchantsOrders)
    {
        return new self(
            $merchantsOrders['merchantId'],
            array_map(function (array $order) {
                return array_key_exists('amount', $order)
                    ? ReturnOrder::createFromArray($order)
                    : DeliveryOrder::createFromArray($order);
            }, $merchantsOrders['orders'])
        );
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return mixed[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @psalm-return array{merchantId: string, orders: array{orderId: string, status: string, amount?: int}[]}
     * @return mixed[]
     */
    public function toArray()
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'orders' => array_map(function (AbstractMerchantOrder $order) {
                return $order->toArray();
            }, $this->getOrders()),
        ];
    }
}
