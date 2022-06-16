<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Order;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\AuthenticationInterface;

class OrderManager
{
    private Api $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public static function create(string $baseUri, ?AuthenticationInterface $authentication = null): OrderManager
    {
        return new self(Api::create($baseUri, $authentication));
    }
}
