<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\PreApp;

use BnplPartners\Factoring004\ArrayInterface;
use InvalidArgumentException;

/**
 * @psalm-immutable
 */
class PartnerData implements ArrayInterface
{
    /**
     * @var string
     */
    private $partnerName;
    /**
     * @var string
     */
    private $partnerCode;
    /**
     * @var string
     */
    private $pointCode;

    public function __construct(string $partnerName, string $partnerCode, string $pointCode)
    {
        $this->partnerName = $partnerName;
        $this->partnerCode = $partnerCode;
        $this->pointCode = $pointCode;
    }

    /**
     * @param array<string, string> $partnerData
     * @psalm-param array{partnerName: string, partnerCode: string, pointCode: string} $partnerData
     *
     * @throws \InvalidArgumentException
     */
    public static function createFromArray($partnerData): PartnerData
    {
        if (empty($partnerData['partnerName'])) {
            throw new InvalidArgumentException("Key 'partnerName' is required");
        }

        if (empty($partnerData['partnerCode'])) {
            throw new InvalidArgumentException("Key 'partnerCode' is required");
        }

        if (empty($partnerData['pointCode'])) {
            throw new InvalidArgumentException("Key 'pointCode' is required");
        }

        return new self($partnerData['partnerName'], $partnerData['partnerCode'], $partnerData['pointCode']);
    }

    public function getPartnerName(): string
    {
        return $this->partnerName;
    }

    public function getPartnerCode(): string
    {
        return $this->partnerCode;
    }

    public function getPointCode(): string
    {
        return $this->pointCode;
    }

    /**
     * @return array<string, string>
     * @psalm-return array{partnerName: string, partnerCode: string, pointCode: string}
     */
    public function toArray(): array
    {
        return [
            'partnerName' => $this->getPartnerName(),
            'partnerCode' => $this->getPartnerCode(),
            'pointCode' => $this->getPointCode(),
        ];
    }
}
