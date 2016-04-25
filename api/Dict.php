<?php

class Dict { 
    
    private $string;
    private $min_words;
    
    private $startYear = 1800;
    private $endYear = 2000;
    
    private $query;
    // private $maxQueryLength = 5000;
    
    private $count;
    private $frequencies;
    
    private $status;
    private $error;
    
    public function __construct($string, $min_words) {
        
        $this->string = $this->forceUtf($string);
        $this->min_words = $min_words;
        
        try {
            $this->setQuery();
            $this->setFrequencies();
            $this->status = 'success';
        } catch (Exception $e) {
            $this->status = 'error';
            $this->error = $e->getMessage();
        }
        
    }
     
    private function forceUtf($text) {
        if(mb_detect_encoding($text, 'UTF-8', true)) {
            return $text;
        } else {
            return utf8_encode($text);
        }
    }
    
    private function setQuery() {

        $string = $this->string;
        
        if ($string == '') { 
            throw new Exception('Query is empty.'); 
        }
        
        $query = '';
        mb_internal_encoding("UTF-8");
        
        $string = trim($string);
        $string = preg_replace('/"+/', '', $string);
        $string = preg_replace('/ +/', ' ', $string);
        $string = preg_replace('/-+/', '-', $string);
        
        for($i = 0; $i < mb_strlen($string); $i++) {
            $char = mb_substr($string, $i, 1);
            if (preg_match('/[a-zA-ZÁáÉéÍíÓóÚúÀàÈèÌìÒòÙùÄäËëÏïÖöÜü*\- ]/', $char)) {
                $query .= $char;    
            } else {
                throw new Exception('Invalid character: "'.$char.'".'); 
            }
        }
        
        if ($query == '' || preg_match('/^[\- ]+$/', $query)) { 
            throw new Exception('Invalid query.'); 
        }
        // $query = str_replace(" ", "+", $query);
        $this->query = $query;
        // print($query);        
    } 

    private function setFrequencies() {
        $solrRequest = new SolrRequest($this->query, $this->min_words, $this->startYear, $this->endYear);
        $this->frequencies = $solrRequest->getFrequencies();
        $this->count = $solrRequest->getCount();
    }
    
    public function getData() {
        if ($this->status == 'success') {
            $responseArray = array(
                'status' => $this->status,
                'string' => $this->string,
                'min_words' => $this->min_words,
                'count' => $this->count, 
                'query' => $this->query, 
                'frequencies' => $this->frequencies
            );
        } else {
            $responseArray = array(
                'status' => $this->status,
                'error' => $this->error
            );
        }
        return $responseArray;   
    }

}
