<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\OAuth;

use BadMethodCallException;
use BnplPartners\Factoring004\Auth\BasicAuth;
use BnplPartners\Factoring004\Exception\OAuthException;
use BnplPartners\Factoring004\Exception\TransportException;
use BnplPartners\Factoring004\Transport\TransportInterface;
use InvalidArgumentException;

class OAuthTokenManager implements OAuthTokenManagerInterface
{
    private const ACCESS_PATH = '/oauth2/token';
    private const REVOKE_PATH = '/oauth2/revoke';

    private TransportInterface $transport;
    private string $baseUri;
    private string $consumerKey;
    private string $consumerSecret;

    public function __construct(TransportInterface $transport, string $baseUri, string $consumerKey, string $consumerSecret)
    {
        if (!$baseUri) {
            throw new InvalidArgumentException('Base URI cannot be empty');
        }

        if (!$consumerKey) {
            throw new InvalidArgumentException('Consumer key cannot be empty');
        }

        if (!$consumerSecret) {
            throw new InvalidArgumentException('Consumer secret cannot be empty');
        }

        $this->transport = $transport;
        $this->baseUri = $baseUri;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    public function getAccessToken(): OAuthToken
    {
        $this->transport->setBaseUri($this->baseUri);
        $this->transport->setAuthentication(new BasicAuth($this->consumerKey, $this->consumerSecret));

        try {
            $response = $this->transport->post(static::ACCESS_PATH, ['grant_type' => 'client_credentials'], [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        } catch (TransportException $e) {
            throw new OAuthException('Cannot generate an access token', 0, $e);
        }

        return OAuthToken::createFromArray($response->getBody());
    }

    public function revokeToken(): void
    {
        throw new BadMethodCallException('Method ' . __FUNCTION__ . ' is not supported');
    }
}
