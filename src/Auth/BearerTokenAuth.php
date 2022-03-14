<?php

namespace BnplPartners\Factoring004\Auth;

use Psr\Http\Message\RequestInterface;

class BearerTokenAuth implements AuthenticationInterface
{
    const HEADER_NAME = 'Authorization';
    const AUTH_SCHEMA = 'Bearer';

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $token = (string) $token;
        $this->token = $token;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function apply($request)
    {
        return $request->withHeader(static::HEADER_NAME, static::AUTH_SCHEMA . ' ' . $this->token);
    }
}
