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
    
            // secure getting data
            $title = $itemInfo['Title']['DisplayValue'] ?? 'Без названия';
            $brand = $itemInfo['ByLineInfo']['Brand']['DisplayValue'] 
                     ?? $itemInfo['ByLineInfo']['Manufacturer']['DisplayValue'] 
                     ?? 'Unknown';
            $price = $offers['Price']['DisplayAmount'] ?? 'No price';
            $features = $itemInfo['Features']['DisplayValues'] ?? [];
            $freeShipping = $offers['DeliveryInfo']['IsFreeShippingEligible'] ?? false;
    
            // secure getting image
            if (isset($item['Images']['Primary']['Large']['URL'])) {
                $image = $item['Images']['Primary']['Large']['URL'];
            } elseif (isset($item['Images']['Pri']['Large']['URL'])) {
                $image = $item['Images']['Pri']['Large']['URL'];
            } else {
                $image = ''; // fallback
            }
    
            // rating
            $rating_score = round(mt_rand(90, 99) / 10, 1); // for example 9.0–9.9
            if($rating_score >= 9.8){
                $rating_stars = 5.0;
                $label = '"Excepcional"';
            } 
            elseif 
                ($rating_score >= 9.2){
                    $rating_stars = 4.5;
                    $label = '"Excelente"';
                } else {
                
                        $rating_stars = 4.2;
                        $label = '"Genial"';
                     

            }
    
            $products[] = [
                'title' => $title,
                'brand' => $brand,
                'price' => $price,
                'features' => $features,
                'freeShipping' => $freeShipping,
                'image' => $image,
                'rating_score' => $rating_score,
                'rating_stars' => $rating_stars,
                'label'=> $label,
                'detail_url' => $item['DetailPageURL'] ?? '#',
            ];
        }
    
        return $products;
    }
}