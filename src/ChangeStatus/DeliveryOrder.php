<?php

namespace BnplPartners\Factoring004\ChangeStatus;

/**
 * @extends AbstractMerchantOrder<DeliveryStatus>
 */
class DeliveryOrder extends AbstractMerchantOrder
{
    /**
     * @param string $orderId
     */
    public function __construct($orderId, DeliveryStatus $status)
    {
        $orderId = (string) $orderId;
        parent::__construct($orderId, $status);
    }

    /**
     * @param array<string, mixed> $order
     * @psalm-param array{orderId: string, status: string} $order
     * @return \BnplPartners\Factoring004\ChangeStatus\DeliveryOrder
     */
    public static function createFromArray($order)
    {
        return new self($order['orderId'], new DeliveryStatus($order['status']));
    }
}
