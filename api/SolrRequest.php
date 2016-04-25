<?php

class SolrRequest {
    
    private $baseUrl = "http://solr.kbresearch.nl/solr/DDD_artikel_research/select?";
    
    private $query;
    private $min_words;
    private $startYear;
    private $endYear;
    
    private $frequencies;
    private $count;
    
    public function __construct($query, $min_words, $startYear, $endYear) {

        $this->query = $query;
        $this->min_words = $min_words;
        $this->startYear = $startYear;
        $this->endYear = $endYear;
        
        $ngramUrlComponent = "q=(spatial:Landelijk*+spatial:Regionaal*)+AND+*+" . urlencode($this->query);
        $minWordsUrlComponent = $this->min_words != 0 ? "&defType=edismax&mm=" . $this->min_words : "";
        $ngramQuery = $ngramUrlComponent."&rows=0&facet=on&facet.limit=-1&facet.prefix=2&facet.field=periode&wt=json".$minWordsUrlComponent;
        //print($this->baseUrl.$ngramQuery);
        //die();

        $ngramResponse = $this->curlExecute($ngramQuery);
        $ngramArray = $this->solr2array($ngramResponse);
        // print_r($ngramArray);
        
        $this->setCount($ngramResponse);
        // print($this->count);

        // $controlQuery = "q=*:*%26rows=0%26facet=true%26facet.limit=-1%26facet.field=titel.pri_jaar%26wt=json";
        // $controlResponse = $this->curlExecute($controlQuery);
        $file = fopen("control.json", "r");
        $controlData = fread($file, filesize("control.json"));
        $controlArray = $this->solr2array($controlData);
        // print_r($controlArray);
 
        $this->setFrequencies($controlArray, $ngramArray);
        
    }
    
    private function curlExecute($query) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300000);
                
        $response = curl_exec($ch);
        if($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
            // throw new Exception('Er is een fout opgetreden bij het ophalen van de zoekresultaten.');
        }
        curl_close($ch);
        
        return $response;
    }
    
    private function solr2array($solrJson) {
        
        $solrArray = json_decode($solrJson, true);
        if($solrArray === null) {
            throw new Exception('JSON decode error.');
            // throw new Exception('Er is een fout opgetreden bij het ophalen van de zoekresultaten.');
        }
        
        if (isset($solrArray['facet_counts']['facet_fields']['periode'])) {
            $solrArrayFacet = $solrArray['facet_counts']['facet_fields']['periode'];
        } else {
            throw new Exception('JSON facet error.');
            // throw new Exception('Er is een fout opgetreden bij het ophalen van de zoekresultaten.');
        }
        
        $ngramArray = array();
        for ($i = 0; $i < count($solrArrayFacet); $i = $i + 2) {
            $year = substr($solrArrayFacet[$i], -5, 4);
            $freq = $solrArrayFacet[$i+1];
            $ngramArray[$year] = $freq;
        }
        return $ngramArray;
    }
    
    private function setFrequencies($controlArray, $ngramArray) {
        $frequencies = array();
        for ($i = $this->startYear; $i <= $this->endYear; $i++) {
            $controlFrequency = isset($controlArray[$i]) ? $controlArray[$i] : 0;
            $absoluteFrequency = isset($ngramArray[$i]) ? $ngramArray[$i] : 0;
            $relativeFrequency = $controlFrequency > 0 ? round($absoluteFrequency / $controlFrequency, 5) : 0;
            $frequencies[] = array('yr' => $i, 'af' => $absoluteFrequency, 'rf' => $relativeFrequency);
        }
        $this->frequencies = $frequencies;
    }
    
    private function setCount($solrJson) {
        $solrArray = json_decode($solrJson, true);
        $solrArrayCount = $solrArray['response']['numFound'];
        $this->count = $solrArrayCount;
    }
    
    public function getFrequencies() {
        return $this->frequencies;
    }
    
    public function getCount() {
        return $this->count;
    }
}
