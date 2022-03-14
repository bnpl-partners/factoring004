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
     * @var string
     */
    private $partnerEmail;
    /**
     * @var string
     */
    private $partnerWebsite;

    /**
     * @param string $partnerName
     * @param string $partnerCode
     * @param string $pointCode
     * @param string $partnerEmail
     * @param string $partnerWebsite
     */
    public function __construct($partnerName, $partnerCode, $pointCode, $partnerEmail, $partnerWebsite)
    {
        $partnerName = (string) $partnerName;
        $partnerCode = (string) $partnerCode;
        $pointCode = (string) $pointCode;
        $partnerEmail = (string) $partnerEmail;
        $partnerWebsite = (string) $partnerWebsite;

        $this->partnerName = $partnerName;
        $this->partnerCode = $partnerCode;
        $this->pointCode = $pointCode;
        $this->partnerEmail = $partnerEmail;
        $this->partnerWebsite = $partnerWebsite;
    }

    /**
     * @param array<string, string> $partnerData
     * @psalm-param array{
           partnerName: string,
           partnerCode: string,
           pointCode: string,
           partnerEmail: string,
           partnerWebsite: string,
      } $partnerData
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

        if (empty($partnerData['partnerEmail'])) {
            throw new InvalidArgumentException("Key 'partnerEmail' is required");
        }

        if (empty($partnerData['partnerWebsite'])) {
            throw new InvalidArgumentException("Key 'partnerWebsite' is required");
        }

        return new self(
            $partnerData['partnerName'],
            $partnerData['partnerCode'],
            $partnerData['pointCode'],
            $partnerData['partnerEmail'],
            $partnerData['partnerWebsite']
        );
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
     * @return string
     */
    public function getPartnerEmail()
    {
        return $this->partnerEmail;
    }

    /**
     * @return string
     */
    public function getPartnerWebsite()
    {
        return $this->partnerWebsite;
    }

    /**
     * @return array<string, string>
     * @psalm-return array{
         partnerName: string,
         partnerCode: string,
         pointCode: string,
         partnerEmail: string,
         partnerWebsite: string,
       }
     */
    public function toArray()
    {
        return [
            'partnerName' => $this->getPartnerName(),
            'partnerCode' => $this->getPartnerCode(),
            'pointCode' => $this->getPointCode(),
            'partnerEmail' => $this->getPartnerEmail(),
            'partnerWebsite' => $this->getPartnerWebsite(),
        ];
    }
}
