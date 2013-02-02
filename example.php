#!/usr/local/bin/php
<?php

require_once 'lib/awsCloudSearch.php';

// Add your search domain and domain id
const DOMAIN_NAME = 'yourdomain';
const DOMAIN_ID = 'yourid';

// Initialize the library
$aws = new awsCloudSearch(DOMAIN_NAME, DOMAIN_ID);

// Search for the value you are looking for
$res = $aws->search('term');

// If the HTTP_CODE is 200 you have a winner
if ($aws->http_code == 200) {
    print_r($res);
} else {
    // else you have an issue
    echo "the code was: " . $aws->http_code;   
}

