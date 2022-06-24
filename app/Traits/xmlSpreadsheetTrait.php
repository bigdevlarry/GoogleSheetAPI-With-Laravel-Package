<?php

namespace App\Traits;

trait xmlSpreadsheetTrait
{
    public function formatXmlDataItemToArray(array $items) :array
    {
        $data = [];
        foreach ($items as $key => $item){
            $data[] = [
                'Entity Id' => $item['entity_id'] ?? '',
                'Category Name' => implode(', ', $item['CategoryName']) ?? '',
                'Sku' => $item['sku'],
                'Name' => implode(', ', $item['name']) ?? '',
                'Description' => implode(', ', $item['description']) ?? '',
                'Shortdesc' => implode(', ', $item['shortdesc']) ?? '',
                'Price' => is_array($item['price']) ? implode(', ', $item['price']) : $item['price'],
                'Link' => $item['link'] ?? '',
                'Image' => $item['image'] ?? '',
                'Brand' => implode(', ', $item['Brand']) ?? '',
                'Rating' => is_array($item['Rating']) ? implode(', ', $item['Rating']) : $item['Rating'],
                'CaffineType' => $item['CaffineType'] ?? '',
                'Count' => is_array($item['Count']) ? implode(', ', $item['Count']) : $item['Count'],
                'Flavored' => is_array($item['Flavored']) ? implode(', ', $item['Flavored']) : $item['Flavored'],
                'Seasonal' => is_array($item['Seasonal']) ? implode(', ', $item['Seasonal']) : $item['Seasonal'],
                'InStock' => $item['InStock'] ?? '',
                'Facebook' => $item['Facebook'] ?? '',
                'IsKCup' => $item['IsKCup'] ?? '',
            ];
        }
        return $data;
    }

    public function decodeXmlDataToArray (string $xmlDataString) :array
    {
        $xmlObject = simplexml_load_string($xmlDataString);
        $json = json_encode($xmlObject);
        return (json_decode($json, true))['item']; 
    }
}


