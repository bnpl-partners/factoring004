<?php

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

    /**
     * @param string $partnerName
     * @param string $partnerCode
     * @param string $pointCode
     */
    public function __construct($partnerName, $partnerCode, $pointCode)
    {
        $partnerName = (string) $partnerName;
        $partnerCode = (string) $partnerCode;
        $pointCode = (string) $pointCode;
        $this->partnerName = $partnerName;
        $this->partnerCode = $partnerCode;
        $this->pointCode = $pointCode;
    }

    /**
     * @param array<string, string> $partnerData
     * @psalm-param array{partnerName: string, partnerCode: string, pointCode: string} $partnerData
     *
     * @throws \InvalidArgumentException
     * @return \BnplPartners\Factoring004\PreApp\PartnerData
     */
    public static function createFromArray($partnerData)
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

    /**
     * @return string
     */
    public function getPartnerName()
    {
        return $this->partnerName;
    }

    /**
     * @return string
     */
    public function getPartnerCode()
    {
        return $this->partnerCode;
    }

    /**
     * @return string
     */
    public function getPointCode()
    {
        return $this->pointCode;
    }

    /**
     * @return mixed[]
     * @psalm-return array{partnerName: string, partnerCode: string, pointCode: string}
     */
    public function toArray()
    {
        return [
            'partnerName' => $this->getPartnerName(),
            'partnerCode' => $this->getPartnerCode(),
            'pointCode' => $this->getPointCode(),
        ];
    }
}
