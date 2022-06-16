<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Order;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use PHPUnit\Framework\TestCase;

class OrderManagerTest extends TestCase
{
    public function testCreate(): void
    {
        $expected = new OrderManager(Api::create('http://example.com'));
        $actual = OrderManager::create('http://example.com');
        $this->assertEquals($expected, $actual);

        $expected = new OrderManager(Api::create('http://example.com', new BearerTokenAuth('Test')));
        $actual = OrderManager::create('http://example.com', new BearerTokenAuth('Test'));
        $this->assertEquals($expected, $actual);
    }
}

