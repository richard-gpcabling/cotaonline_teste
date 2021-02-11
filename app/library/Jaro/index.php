<?php

require 'ProdutoSearch.php';

use App\Libraries\Jaro\JaroDistance;
use App\Libraries\Jaro\ProdutoSearch;

$produtoSearch = new ProdutoSearch();
//$produtoSearch->generateFileData();
$query = 'N120';

$data = $produtoSearch->getData();

echo '<pre>';print_r("Query: $query");echo '</pre>';
echo '<pre>';print_r(' ');echo '</pre>';

foreach ($data as $dataItem) {
    echo '<pre>';print_r("Jaro: ('$dataItem', '$query') = " . JaroDistance::jaro($dataItem, $query));echo '</pre>';
    echo '<pre>';print_r("Jaro-Winkler: ('$dataItem', '$query') = " . JaroDistance::jaroWinkler($dataItem, $query));echo '</pre>';
    echo '<pre>';print_r(' ');echo '</pre>';
}