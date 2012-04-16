<?php

/*
AWS Cloud Search Library 
By: Greg Avola (@gregavola)
*/


class awsCloudSearch {
    
    public $search_domain;
    public $domain_id;
    public $search_host;

    public $document_endpoint;
    public $search_endpoint;

    public $http_code = 200;

    public $calendar_method = "2011-02-01";

    public $availableTypes = array("update", "add", "delete");
    
	/**
   * Basic constructor
   *
   */

    public function __construct($search_domain, $domain_id)
    {
        $this->search_domain = $search_domain;
        $this->domain_id = $domain_id; 
        
        $this->search_host = "http://doc-".$this->search_domain."-".$this->domain_id.".us-east-1.cloudsearch.amazonaws.com";

        $this->document_endpoint = "http://doc-".$this->search_domain."-".$this->domain_id.".us-east-1.cloudsearch.amazonaws.com/" . $this->calendar_method;
        $this->search_endpoint = "http://search-".$this->search_domain."-".$this->domain_id.".us-east-1.cloudsearch.amazonaws.com/" . $this->calendar_method;       

    }

	/**
   * Public document API call
   *
   * @param $type - add, deete, $params - the ranking algorithms or other variables
   */

    public function document($type, $params = array())
    {
        if (in_array($type, $this->availableTypes)) {
            return $this->call($this->document_endpoint ."/documents/batch", "POST", json_encode($params));
        }
        else {
            // perform error
        }
    } 

	/**
   * Public search API call
   *
   * @param $term - the search term, $params - the ranking algorithms or other variables
   */

    public function search($term, $params = array())
    {
        if (sizeof($params) == 0) {
            return $this->call($this->search_endpoint ."/search?q=".urlencode($term), "GET", array());
        }
        else {
            return $this->call($this->search_endpoint ."/search?q=".urlencode($term)."&".http_build_query($params), "GET", array());
        }
    }
	
	/**
   * Private function that return the results of a GET or POST call to your domain host.
   *
   * @param $url - url to send to, $method - the GET or POST, $parameters - the params to pass
   */
	
    private function call($url, $method, $parameters) {
        
        $curl2 = curl_init();
        
        if ($method == "POST")
        {
            curl_setopt($curl2, CURLOPT_POST, true);
            curl_setopt($curl2, CURLOPT_POSTFIELDS, $parameters);
            
            curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($parameters))                                                                   
            );
            
        }

        curl_setopt($curl2, CURLOPT_URL, $url);
        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
  
        $result = curl_exec($curl2);
        
        $HttpCode = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
        
        $this->http_code = (int)$HttpCode;

        return $result;
    }  
}    


?>