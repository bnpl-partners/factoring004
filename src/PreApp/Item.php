<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\PreApp;

use BnplPartners\Factoring004\ArrayInterface;

/**
 * @psalm-immutable
 */
class Item implements ArrayInterface
{
    /**
     * @var string
     */
    private $itemId;
    /**
     * @var string
     */
    private $itemName;
    /**
     * @var string
     */
    private $itemCategory;
    /**
     * @var int
     */
    private $itemQuantity;
    /**
     * @var int
     */
    private $itemPrice;
    /**
     * @var int
     */
    private $itemSum;

    public function __construct(
        string $itemId,
        string $itemName,
        string $itemCategory,
        int $itemQuantity,
        int $itemPrice,
        int $itemSum
    ) {
        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->itemCategory = $itemCategory;
        $this->itemQuantity = $itemQuantity;
        $this->itemPrice = $itemPrice;
        $this->itemSum = $itemSum;
    }

    /**
     * @param array<string, mixed> $item
     * @psalm-param array{
           itemId: string,
           itemName: string,
           itemCategory: string,
           itemQuantity: int,
           itemPrice: int,
           itemSum: int,
     * } $item
     *
     * @return \BnplPartners\Factoring004\PreApp\Item
     */
    public static function createFromArray($item): Item
    {
        return new self($item['itemId'], $item['itemName'], $item['itemCategory'], $item['itemQuantity'], $item['itemPrice'], $item['itemSum']);
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getItemCategory(): string
    {
        return $this->itemCategory;
    }

    public function getItemQuantity(): int
    {
        return $this->itemQuantity;
    }

    public function getItemPrice(): int
    {
        return $this->itemPrice;
    }

    public function getItemSum(): int
    {
        return $this->itemSum;
    }

    /**
     * @psalm-return array{
           itemId: string,
           itemName: string,
           itemCategory: string,
           itemQuantity: int,
           itemPrice: int,
           itemSum: int,
     * }
     */
    public function toArray(): array
    {
        return [
            'itemId' => $this->getItemId(),
            'itemName' => $this->getItemName(),
            'itemCategory' => $this->getItemCategory(),
            'itemQuantity' => $this->getItemQuantity(),
            'itemPrice' => $this->getItemPrice(),
            'itemSum' => $this->getItemSum(),
        ];
    }
}
