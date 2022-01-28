<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Transport;

use BnplPartners\Factoring004\Auth\ApiKeyAuth;
use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\Auth\BasicAuth;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Exception\DataSerializationException;
use BnplPartners\Factoring004\Exception\NetworkException;
use BnplPartners\Factoring004\Exception\TransportException;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class TransportTest extends TestCase
{
    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testBaseUriIsEmpty(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => (string) $request->getUri() === '/'))
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->get('/');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testSetBaseUri(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => (string) $request->getUri() === 'http://example.com/'))
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->setBaseUri('http://example.com');
        $transport->get('/');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testSetBaseUriWithPath(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => (string) $request->getUri() === 'http://example.com/1.0/preapp'))
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->setBaseUri('http://example.com/1.0');
        $transport->get('/preapp');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testHeadersIsEmpty(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => empty($request->getHeaders())))
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->get('/');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testSetHeaders(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'Test',
        ];

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => $request->getHeaders() === array_map(fn($item) => [$item], $headers)))
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->setHeaders($headers);
        $transport->get('/');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testOverrideHeaders(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => $request->getHeaders() === [
                    'Content-Type' => ['application/x-www-form-urlencoded'],
                    'Accept' => ['application/json'],
                ])
            )
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->setHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $transport->get('/', [], ['Content-Type' => 'application/x-www-form-urlencoded']);
    }

    /**
     * @dataProvider authenticationsProvider
     */
    public function testSetAuthentication(
        AuthenticationInterface $authentication,
        string $expectedHeaderName,
        string $expectedHeaderValue
    ): void {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->callback(fn($request) => $request->getHeaderLine($expectedHeaderName) === $expectedHeaderValue)
            )
            ->willReturn(new PsrResponse(200, [], '{}'));

        $transport = $this->createTransport($client);
        $transport->setAuthentication($authentication);

        $transport->get('/');
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testGet(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => $request->getUri()->getPath() === '/test'))
            ->willReturn(new PsrResponse(200, [], '{"status": true, "message": "text"}'));

        $transport = $this->createTransport($client);

        $this->assertEquals(new Response(200, [], ['status' => true, 'message' => 'text']), $transport->get('/test'));
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testGetWithQuery(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $query = [
            'a' => 15,
            'b' => 40,
            'c' => [1, 2, 3],
        ];

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(fn($request) => $request->getUri()->getQuery() === http_build_query($query)))
            ->willReturn(new PsrResponse(200, [], '{"status": true, "message": "text"}'));

        $transport = $this->createTransport($client);
        $transport->get('/test', $query);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testPost(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getUri()->getPath() === '/test' && empty($request->getBody()->getContents());
            }))
            ->willReturn(new PsrResponse(200, [], '{"status": false, "message": "error"}'));

        $transport = $this->createTransport($client);

        $this->assertEquals(new Response(200, [], ['status' => false, 'message' => 'error']), $transport->get('/test'));
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    public function testPostWithBody(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getBody()->getContents() === '{"a":15,"b":40,"c":[1,2,3]}';
            }))
            ->willReturn(new PsrResponse(200, [], '{"status": false, "message": "error"}'));

        $transport = $this->createTransport($client);
        $transport->post('/test', ['a' => 15, 'b' => 40, 'c' => [1, 2, 3]]);
    }

    /**
     * @dataProvider queryParametersProvider
     */
    public function testRequestWithQueryParameters(string $method, array $query, string $expectedQuery): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($method, $expectedQuery) {
                return $request->getMethod() === $method && $request->getUri()->getQuery() === $expectedQuery;
            }))
            ->willReturn(new PsrResponse(200, [], '{"status": true, "message": "text"}'));

        $transport = $this->createTransport($client);
        $transport->request($method, '/test', $query);
    }

    /**
     * @dataProvider dataParametersProvider
     */
    public function testRequestWithDataParameters(string $method, array $data, string $expectedData): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($method, $expectedData) {
                return $request->getMethod() === $method && $request->getBody()->getContents() === $expectedData;
            }))
            ->willReturn(new PsrResponse(200, [], '{"status": true, "message": "text"}'));

        $transport = $this->createTransport($client);
        $transport->request($method, '/test', $data);
    }

    /**
     * @dataProvider psrClientExceptionsProvider
     */
    public function testWithClientException(string $exceptionClass, string $expectedExceptionClass): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->willThrowException($this->createStub($exceptionClass));

        $this->expectException($expectedExceptionClass);

        $transport = $this->createTransport($client);
        $transport->get('/test');
    }

    public function testWithInvalidJsonMessage(): void
    {
        $client = $this->createStub(ClientInterface::class);

        $this->expectException(DataSerializationException::class);

        $transport = $this->createTransport($client);
        $transport->post('/test', ['file' => tmpfile()]);
    }

    public function testWithInvalidJsonResponse(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new PsrResponse(200, [], '{"a:15}'));

        $this->expectException(DataSerializationException::class);

        $transport = $this->createTransport($client);
        $transport->get('/test');
    }

    public function queryParametersProvider(): array
    {
        return [
            [
                'HEAD',
                [],
                '',
            ],
            [
                'GET',
                ['a' => 15, 'b' => 20, 'c' => ['x' => 'str', 'y' => '100', 'c' => 200]],
                'a=15&b=20&c%5Bx%5D=str&c%5By%5D=100&c%5Bc%5D=200',
            ],
            [
                'OPTIONS',
                ['a' => 'one', 'b' => 'two', 'c' => ['a', 'b', 'c']],
                'a=one&b=two&c%5B0%5D=a&c%5B1%5D=b&c%5B2%5D=c',
            ],
            [
                'DELETE',
                ['a' => [1, 2, 3], 'b' => ['a' => 'a', 'b' => 'c'], 'c' => []],
                'a%5B0%5D=1&a%5B1%5D=2&a%5B2%5D=3&b%5Ba%5D=a&b%5Bb%5D=c',
            ],
        ];
    }

    public function dataParametersProvider(): array
    {
        return [
            [
                'POST',
                ['a' => 15, 'b' => 20, 'c' => ['x' => 'str', 'y' => '100', 'c' => 200]],
                '{"a":15,"b":20,"c":{"x":"str","y":"100","c":200}}',
            ],
            [
                'PUT',
                ['a' => 'one', 'b' => 'two', 'c' => ['a', 'b', 'c']],
                '{"a":"one","b":"two","c":["a","b","c"]}',
            ],
            [
                'PATCH',
                [],
                '',
            ],
        ];
    }

    public function authenticationsProvider(): array
    {
        return [
            [new BearerTokenAuth('test'), 'Authorization', 'Bearer test'],
            [new ApiKeyAuth('test'), 'apiKey', 'test'],
            [new BasicAuth('test', 'test'), 'Authorization', 'Basic ' . base64_encode('test:test')],
        ];
    }

    public function psrClientExceptionsProvider(): array
    {
        return [
            [NetworkExceptionInterface::class, NetworkException::class],
            [RequestExceptionInterface::class, TransportException::class],
            [ClientExceptionInterface::class, TransportException::class],
        ];
    }

    private function createTransport(ClientInterface $client): Transport
    {
        return new Transport(
            new HttpFactory(),
            new HttpFactory(),
            new HttpFactory(),
            $client,
        );
    }
}

