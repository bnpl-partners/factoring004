<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004;

use BnplPartners\Factoring004\Auth\AuthenticationInterface;
use BnplPartners\Factoring004\PreApp\PreAppResource;
use BnplPartners\Factoring004\Transport\TransportInterface;
use OutOfBoundsException;

/**
 * @property-read \BnplPartners\Factoring004\PreApp\PreAppResource $preApps
 */
class Api
{
    private PreAppResource $preApps;

    public function __construct(
        TransportInterface $transport,
        string $baseUri,
        ?AuthenticationInterface $authentication = null
    ) {
        $this->preApps = new PreAppResource($transport, $baseUri, $authentication);
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

        throw new OutOfBoundsException("Property {$name} does not exist");
    }
}
