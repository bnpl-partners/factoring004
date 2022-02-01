<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\Transport;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\Auth\NoAuth;
use BnplPartners\Factoring004\Exception\DataSerializationException;
use BnplPartners\Factoring004\Exception\NetworkException;
use BnplPartners\Factoring004\Exception\TransportException;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class Transport implements TransportInterface
{
    protected const METHODS_WITHOUT_BODY = ['GET', 'HEAD', 'OPTIONS', 'DELETE'];
    protected const DEFAULT_CONTENT_TYPE = 'application/json';

    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;
    protected UriFactoryInterface $uriFactory;
    protected ClientInterface $client;

    /**
     * @var array<string, string>
     */
    protected array $headers = [];
    protected UriInterface $baseUri;
    protected AuthenticationInterface $authentication;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory,
        ClientInterface $client
    ) {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
        $this->client = $client;
        $this->baseUri = $uriFactory->createUri();
        $this->authentication = new NoAuth();
    }

    public function setBaseUri(string $uri): TransportInterface
    {
        $this->baseUri = $this->uriFactory->createUri($uri);
        return $this;
    }

    public function setHeaders(array $headers): TransportInterface
    {
        $this->headers = $headers;
        return $this;
    }

    public function setAuthentication(AuthenticationInterface $authentication): TransportInterface
    {
        $this->authentication = $authentication;
        return $this;
    }

    public function get(string $path, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->request(__FUNCTION__, $path, $query, $headers);
    }

    public function post(string $path, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request(__FUNCTION__, $path, $data, $headers);
    }

    public function request(string $method, string $path, array $data = [], array $headers = []): ResponseInterface
    {
        $request = $this->prepareRequest(strtoupper($method), $path, $data, $headers);
        $response = $this->sendRequest($request);

        return $this->convertResponse($response);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     */
    protected function prepareRequest(string $method, string $path, array $data, array $headers): RequestInterface
    {
        $isWithoutBody = in_array($method, static::METHODS_WITHOUT_BODY, true);
        $query = $isWithoutBody ? $data : [];

        $request = $this->createRequest($method, $path, $query);
        $request = $this->mergeRequestHeaders($request, $headers);

        if (!$isWithoutBody) {
            $stream = $this->createStream($this->serializeData($data, $request));
            $request = $request->withBody($stream);
        }

        return $this->authentication->apply($request);
    }

    /**
     * @param array<string, mixed> $query
     */
    protected function createRequest(string $method, string $path, array $query = []): RequestInterface
    {
        $path = rtrim($this->baseUri->getPath(), '/') . '/' . ltrim($path, '/');
        $uri = $this->baseUri->withPath($path);

        if ($query) {
            $uri = $uri->withQuery(http_build_query($query));
        }

        return $this->requestFactory->createRequest($method, $uri);
    }

    /**
     * @param array<string, string> $headers
     */
    protected function mergeRequestHeaders(RequestInterface $request, array $headers): RequestInterface
    {
        $headers = array_merge($this->headers, $headers);

        foreach ($headers as $name => $header) {
            $request = $request->withHeader($name, $header);
        }

        return $request;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     */
    protected function serializeData(array $data, RequestInterface $request): string
    {
        if (!$data) {
            return '';
        }

        $contentType = $request->getHeaderLine('Content-Type') ?: static::DEFAULT_CONTENT_TYPE;

        if (strpos($contentType, 'json') !== false) {
            try {
                return json_encode($data, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                throw new DataSerializationException('Invalid JSON format', 0, $e);
            }
        }

        if ($contentType === 'application/x-www-form-urlencoded') {
            return http_build_query($data);
        }

        throw new DataSerializationException('Unsupported content type ' . $contentType);
    }

    protected function createStream(string $content): StreamInterface
    {
        return $this->streamFactory->createStream($content);
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\NetworkException
     * @throws \BnplPartners\Factoring004\Exception\TransportException
     */
    protected function sendRequest(RequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->client->sendRequest($request);
        } catch (NetworkExceptionInterface $e) {
            throw new NetworkException('Network issues of ' . $e->getRequest()->getUri(), 0, $e);
        } catch (ClientExceptionInterface $e) {
            throw new TransportException('Unable to send request to ' . $request->getUri(), 0, $e);
        }
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\DataSerializationException
     */
    protected function convertResponse(\Psr\Http\Message\ResponseInterface $response): ResponseInterface
    {
        return Response::createFromPsrResponse($response);
    }
}
