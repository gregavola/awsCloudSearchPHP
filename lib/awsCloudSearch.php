<?php

/**
 * AWS (Lightweight) Cloud Search Library
 *
 * @author Greg Avola (@gregavola)
 * @author Tamas Kalman <ktamas77@gmail.com>
 */
class awsCloudSearch
{

    public $search_domain;
    public $domain_id;
    public $search_host;
    public $document_endpoint;
    public $search_endpoint;
    public $http_code = 200;
    public $calendar_method = '2011-02-01';
    public $availableTypes = array('update', 'add', 'delete');

    /**
     * Constructor
     *
     * @param String $search_domain Search Domain
     * @param String $domain_id     Domain ID
     *
     * @return void
     */
    public function __construct($search_domain, $domain_id)
    {
        $this->search_domain = $search_domain;
        $this->domain_id = $domain_id;
        $this->search_host = sprintf('http://doc-%s-%s.us-east-1.cloudsearch.amazonaws.com', $this->search_domain, $this->domain_id);
        $this->document_endpoint = sprintf('http://doc-%s-%s.us-east-1.cloudsearch.amazonaws.com/%s', $this->search_domain, $this->domain_id, $this->calendar_method);
        $this->search_endpoint = sprintf('http://search-%s-%s.us-east-1.cloudsearch.amazonaws.com/%s', $this->search_domain, $this->domain_id, $this->calendar_method);
    }

    /**
     * Public document API call
     *
     * @param String $type   'add' or 'delete'
     * @param Array  $params The ranking algorithms or other variables
     *
     * @return Mixed Result
     */
    public function document($type, $params = array())
    {
        if (in_array($type, $this->availableTypes)) {
            return $this->call($this->document_endpoint . '/documents/batch', 'POST', json_encode($params));
        } else {
            // perform error
        }
    }

    /**
     * Public search API call
     *
     * @param String $term   The search term
     * @param String $params The ranking algorithms or other variables
     *
     * @return Mixed Result
     */
    public function search($term, $params = array())
    {
        if (sizeof($params) == 0) {
            return $this->call($this->search_endpoint . "/search?q=" . urlencode($term), "GET", array());
        } else {
            return $this->call($this->search_endpoint . "/search?q=" . urlencode($term) . "&" . http_build_query($params), "GET", array());
        }
    }

    /**
     * Private function that return the results of a GET or POST call to your domain host.
     *
     * @param String $url        Url to send to
     * @param String $method     'GET' or 'POST'
     * @param Array  $parameters the params to pass to CURL
     *
     * @return Mixed Result
     */
    private function call($url, $method, $parameters)
    {

        $curl2 = curl_init();
        if ($method == "POST") {
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
        $this->http_code = (int) $HttpCode;
        return $result;
    }

}