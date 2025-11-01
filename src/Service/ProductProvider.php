<?php

namespace App\Service;

class ProductProvider
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getProducts(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);

        $items = $data['SearchResult']['Items'] ?? $data;
        $products = [];

        foreach ($items as $item) {
            $itemInfo = $item['ItemInfo'] ?? [];
            $offers = $item['Offers']['Listings'][0] ?? [];

            $products[] = [
                'title' => $itemInfo['Title']['DisplayValue'] ?? 'No title',
                'brand' => $itemInfo['ByLineInfo']['Brand']['DisplayValue'] ?? $itemInfo['Manufacturer']['DisplayValue'] ?? 'Unknown',
                'price' => $offers['Price']['DisplayAmount'] ?? 'No price',
                'features' => $itemInfo['Features']['DisplayValues'] ?? [],
                'freeShipping' => $offers['DeliveryInfo']['IsFreeShippingEligible'] ?? false,
            ];
        }

        return $products;
    }
}