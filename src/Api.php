<?php

namespace BnplPartners\Factoring004;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\ChangeStatus\ChangeStatusResource;
use BnplPartners\Factoring004\Otp\OtpResource;
use BnplPartners\Factoring004\PreApp\PreAppResource;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004\Transport\TransportInterface;
use OutOfBoundsException;

/**
 * @property-read \BnplPartners\Factoring004\PreApp\PreAppResource $preApps
 * @property-read \BnplPartners\Factoring004\Otp\OtpResource $otp
 * @property-read \BnplPartners\Factoring004\ChangeStatus\ChangeStatusResource $changeStatus
 */
class Api
{
    /**
     * @var \BnplPartners\Factoring004\PreApp\PreAppResource
     */
    private $preApps;
    /**
     * @var \BnplPartners\Factoring004\Otp\OtpResource
     */
    private $otp;
    /**
     * @var \BnplPartners\Factoring004\ChangeStatus\ChangeStatusResource
     */
    private $changeStatus;

    /**
     * @param \BnplPartners\Factoring004\Auth\AuthenticationInterface|null $authentication
     * @param \BnplPartners\Factoring004\Transport\TransportInterface|null $transport
     * @param string $baseUri
     */
    public function __construct(
        $baseUri,
        $authentication = null,
        $transport = null
    ) {
        $baseUri = (string) $baseUri;
        $transport = isset($transport) ? $transport : new GuzzleTransport();

        $this->preApps = new PreAppResource($transport, $baseUri, $authentication);
        $this->otp = new OtpResource($transport, $baseUri, $authentication);
        $this->changeStatus = new ChangeStatusResource($transport, $baseUri, $authentication);
    }

    /**
     * @param \BnplPartners\Factoring004\Auth\AuthenticationInterface|null $authentication
     * @param \BnplPartners\Factoring004\Transport\TransportInterface|null $transport
     * @param string $baseUri
     * @return \BnplPartners\Factoring004\Api
     */
    public static function create(
        $baseUri,
        $authentication = null,
        $transport = null
    ) {
        return new self($baseUri, $authentication, $transport);
    }

    /**
     * @param string $name
     * @return \BnplPartners\Factoring004\AbstractResource
     */
    public function __get($name)
    {
        $name = (string) $name;
        if ($name === 'preApps') {
            return $this->preApps;
        }

        if ($name === 'otp') {
            return $this->otp;
        }

        if ($name === 'changeStatus') {
            return $this->changeStatus;
        }

        throw new OutOfBoundsException("Property {$name} does not exist");
    }
}
