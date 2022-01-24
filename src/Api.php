<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\Otp\OtpResource;
use BnplPartners\Factoring004\PreApp\PreAppResource;
use BnplPartners\Factoring004\Transport\TransportInterface;
use OutOfBoundsException;

/**
 * @property-read \BnplPartners\Factoring004\PreApp\PreAppResource $preApps
 * @property-read \BnplPartners\Factoring004\Otp\OtpResource $otp
 */
class Api
{
    private PreAppResource $preApps;
    private OtpResource $otp;

    public function __construct(
        TransportInterface $transport,
        string $baseUri,
        ?AuthenticationInterface $authentication = null
    ) {
        $this->preApps = new PreAppResource($transport, $baseUri, $authentication);
        $this->otp = new OtpResource($transport, $baseUri, $authentication);
    }

    public static function create(
        TransportInterface $transport,
        string $baseUri,
        ?AuthenticationInterface $authentication = null
    ): Api {
        return new self($transport, $baseUri, $authentication);
    }

    public function __get(string $name): AbstractResource
    {
        if ($name === 'preApps') {
            return $this->preApps;
        }

        if ($name === 'otp') {
            return $this->otp;
        }

        throw new OutOfBoundsException("Property {$name} does not exist");
    }
}
