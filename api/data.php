<?php
error_reporting(E_ALL);

include_once 'SolrRequest.php';
include_once 'Dict.php';

header('Content-Type: text/plain; charset=utf-8');

$string = isset($_GET['q']) && is_string($_GET['q']) ? $_GET['q'] : '';
$min_words = isset($_GET['mw']) && in_array($_GET['mw'], array(1,2,3,4,5,10,15)) ? $_GET['mw'] : 1;

$dict = new Dict($string, $min_words);
$ajaxResponse = json_encode($dict->getData());

echo $ajaxResponse;

