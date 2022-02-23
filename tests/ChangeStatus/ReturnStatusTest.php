<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use PHPUnit\Framework\TestCase;

class ReturnStatusTest extends TestCase
{
    public function testRE_TURN(): void
    {
        $this->assertEquals(ReturnStatus::RE_TURN(), ReturnStatus::from('return'));
    }

    public function testPARTRETURN(): void
    {
        $this->assertEquals(ReturnStatus::PARTRETURN(), ReturnStatus::from('partReturn'));
    }
}

