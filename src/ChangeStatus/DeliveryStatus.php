<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use MyCLabs\Enum\Enum;

/**
 * @method static static DELIVERY()
 *
 * @psalm-immutable
 */
final class DeliveryStatus extends Enum
{
    private const DELIVERY = 'delivery';
}
