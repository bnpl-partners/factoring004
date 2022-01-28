<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\PreApp;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PartnerDataTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $expected = new PartnerData('a', 'b', 'c');
        $actual = PartnerData::createFromArray(['partnerName' => 'a', 'partnerCode' => 'b', 'pointCode' => 'c']);
        $this->assertEquals($expected, $actual);

        $expected = new PartnerData('name', 'code', 'test');
        $actual = PartnerData::createFromArray(['partnerName' => 'name', 'partnerCode' => 'code', 'pointCode' => 'test']);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param array<string, string> $partnerData
     *
     * @dataProvider invalidArraysProvider
     */
    public function testCreateFromArrayFailed(array $partnerData): void
    {
        $this->expectException(InvalidArgumentException::class);

        PartnerData::createFromArray($partnerData);
    }

    public function testGetPartnerName(): void
    {
        $partnerData = new PartnerData('a', 'b', 'c');
        $this->assertEquals('a', $partnerData->getPartnerName());

        $partnerData = new PartnerData('test', 'b', 'c');
        $this->assertEquals('test', $partnerData->getPartnerName());
    }

    public function testGetPointCode(): void
    {
        $partnerData = new PartnerData('a', 'b', 'c');
        $this->assertEquals('c', $partnerData->getPointCode());

        $partnerData = new PartnerData('a', 'b', 'test');
        $this->assertEquals('test', $partnerData->getPointCode());
    }

    public function testGetPartnerCode(): void
    {
        $partnerData = new PartnerData('a', 'b', 'c');
        $this->assertEquals('b', $partnerData->getPartnerCode());

        $partnerData = new PartnerData('a', 'test', 'c');
        $this->assertEquals('test', $partnerData->getPartnerCode());
    }

    public function testToArray(): void
    {
        $partnerData = new PartnerData('a', 'b', 'c');
        $this->assertEquals(['partnerName' => 'a', 'partnerCode' => 'b', 'pointCode' => 'c'], $partnerData->toArray());

        $partnerData = new PartnerData('name', 'code', 'test');
        $this->assertEquals(['partnerName' => 'name', 'partnerCode' => 'code', 'pointCode' => 'test'], $partnerData->toArray());
    }

    public function invalidArraysProvider(): array
    {
        return [
            [[]],
            [['partnerName' => 'a']],
            [['partnerCode' => 'b']],
            [['pointCode' => 'c']],
            [['partnerName' => 'a', 'partnerCode' => 'b']],
            [['partnerName' => 'a', 'pointCode' => 'c']],
            [['partnerCode' => 'b', 'pointCode' => 'c']],
        ];
    }
}

