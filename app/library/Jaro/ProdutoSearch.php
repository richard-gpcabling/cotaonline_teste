<?php

namespace App\Libraries\Jaro;

require 'JaroDistance.php';

class ProdutoSearch
{
    private $data;

    const FILE_DATA = 'data.json';

    const FILE_DATA_CLEAN = 'data-clean.json';

    const SEARCH_MIN_SCORE = 0.70;

    public function searchByWord($query)
    {
        $this->loadData();

        $result = [];
        foreach ($this->data as $itemData) {
            $score = JaroDistance::jaro($itemData, $query);

            if ($score >= self::SEARCH_MIN_SCORE) {
                $result[] = ['word' => $itemData, 'query' => $query, 'score' => $score];
            }
        }
        return $result;
    }

    public function generateFileData()
    {
        $fileContent = trim(file_get_contents(self::FILE_DATA_CLEAN));

        $splitData = explode(',', $fileContent);

        file_put_contents(self::FILE_DATA, json_encode($splitData));
    }

    public function getData()
    {
        $this->loadData();
        return $this->data;
    }

    private function loadData()
    {
        $this->data = json_decode(file_get_contents(self::FILE_DATA));
    }
}
