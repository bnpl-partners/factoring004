<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\ChangeStatus;

use PHPUnit\Framework\TestCase;

class SuccessResponseTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $expected = new SuccessResponse('test', 'message');
        $actual = SuccessResponse::createFromArray(['error' => 'test', 'msg' => 'message']);

        $this->assertEquals($expected, $actual);
    }

    public function testGetError(): void
    {
        $response = new SuccessResponse('test', 'message');
        $this->assertEquals('test', $response->getError());

        $response = new SuccessResponse('error', 'message');
        $this->assertEquals('error', $response->getError());
    }

    public function testGetMsg(): void
    {
        $response = new SuccessResponse('test', 'message');
        $this->assertEquals('message', $response->getMsg());

        $response = new SuccessResponse('error', 'test');
        $this->assertEquals('test', $response->getMsg());
    }

    public function testToArray(): void
    {
        $response = new SuccessResponse('test', 'message');
        $expected = [
            'error' => 'test',
            'msg' => 'message',
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    public function testJsonSerialize(): void
    {
        $response = new SuccessResponse('test', 'message');
        $expected = [
            'error' => 'test',
            'msg' => 'message',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($response));
    }
}

