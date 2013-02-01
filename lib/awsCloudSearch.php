<?php

/**
 * AWS (Lightweight) Cloud Search Library
 *
 * @author Greg Avola (@gregavola)
 * @author Tamas Kalman <ktamas77@gmail.com>
 */
class awsCloudSearch
{
    const DOC_API_URL = 'http://doc-%s-%s.us-east-1.cloudsearch.amazonaws.com/%s';
    const SEARCH_API_URL = 'http://search-%s-%s.us-east-1.cloudsearch.amazonaws.com/%s';

    public $searchDomain;
    public $domainId;
    public $documentEndpoint;
    public $searchEndpoint;
    public $httpCode = 200;
    public $calendarMethod = '2011-02-01';
    public $availableTypes = array('update', 'add', 'delete');

    /**
     * Constructor
     *
     * @param String $searchDomain Search Domain
     * @param String $domainId     Domain ID
     *
     * @return void
     */
    public function __construct($searchDomain, $domainId)
    {
        $this->searchDomain = $searchDomain;
        $this->domainId = $domainId;
        $this->documentEndpoint = sprintf(self::DOC_API_URL, $this->searchDomain, $this->domainId, $this->calendarMethod);
        $this->searchEndpoint = sprintf(self::SEARCH_API_URL, $this->searchDomain, $this->domainId, $this->calendarMethod);
    }

    /**
     * Public document API call
     *
     * @param String $type   'add', 'delete' or 'update'
     * @param Array  $params The ranking algorithms or other variables
     *
     * @return Mixed Result
     */
    public function document($type, $params = array())
    {
        if (in_array($type, $this->availableTypes)) {
            return $this->call($this->documentEndpoint . '/documents/batch', 'POST', json_encode($params));
        }
        return false;
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
        $queryParams = (sizeof($params) > 0) ? '&' . http_build_query($params) : '';
        return $this->call($this->searchEndpoint . "/search?q=" . urlencode($term) . $queryParams);
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
    private function call($url, $method = 'GET', $parameters = array())
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
        $this->httpCode = (int) curl_getinfo($curl2, CURLINFO_HTTP_CODE);
        return $result;
    }

}