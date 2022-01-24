<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

/**
 * @extends AbstractMerchantOrder<DeliveryStatus>
 */
class DeliveryOrder extends AbstractMerchantOrder
{
    public function __construct(string $orderId, DeliveryStatus $status)
    {
        parent::__construct($orderId, $status);
    }

    /**
     * @param array<string, mixed> $order
     * @psalm-param array{orderId: string, status: string} $order
     */
    public static function createFromArray(array $order): DeliveryOrder
    {
        return new self($order['orderId'], DeliveryStatus::from($order['status']));
    }

    public function getStatus(): DeliveryStatus
    {
        return $this->status;
    }
}
