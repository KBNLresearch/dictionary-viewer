<?php

error_reporting(E_ALL);

// header('Content-Type: text/csv; charset=utf-8');

// die($_POST['json']);

$frequencies = json_decode($_POST['json']);

// var_dump($frequencies);

$csv = arr2csv($frequencies);

echo $csv;

function arr2csv($arr) {
    
    $csv = 'year,absolute frequency,relative frequency' . "\n";
    
    for ($i = 0; $i < count($arr); $i++) {
        $csv .= $arr[$i]->yr . ',';
        $csv .= $arr[$i]->af . ',';
        $csv .= $arr[$i]->rf;
        $csv .= "\n";
    }
    
    return $csv;
    
}