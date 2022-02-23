<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use MyCLabs\Enum\Enum;

/**
 * @method static static RE_TURN()
 * @method static static PARTRETURN()
 *
 * @psalm-immutable
 */
final class ReturnStatus extends Enum
{
    private const RE_TURN = 'return';
    private const PARTRETURN = 'partReturn';
}
