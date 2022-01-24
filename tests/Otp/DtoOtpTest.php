<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Otp;

use PHPUnit\Framework\TestCase;

class DtoOtpTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $expected = new DtoOtp('test');
        $actual = DtoOtp::createFromArray(['msg' => 'test']);

        $this->assertEquals($expected, $actual);
    }

    public function testGetMsg(): void
    {
        $otp = new DtoOtp('test');
        $this->assertEquals('test', $otp->getMsg());

        $otp = new DtoOtp('message');
        $this->assertEquals('message', $otp->getMsg());
    }

    public function testToArray(): void
    {
        $otp = new DtoOtp('test');
        $this->assertEquals(['msg' => 'test'], $otp->toArray());

        $otp = new DtoOtp('message');
        $this->assertEquals(['msg' => 'message'], $otp->toArray());
    }

    public function testJsonSerialize(): void
    {
        $otp = new DtoOtp('test');
        $this->assertJsonStringEqualsJsonString('{"msg":"test"}', json_encode($otp));

        $otp = new DtoOtp('message');
        $this->assertJsonStringEqualsJsonString('{"msg":"message"}', json_encode($otp));
    }
}

