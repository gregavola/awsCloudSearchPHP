<?php

include ("awsCloudSearch.php");

// Add your search domain and domain id
$aws = new awsCloudSearch(DOMAIN_NAME, DOMAIN_ID);

// search for the value you are looking for
$res = $aws->search("term");

// if the HTTP_CODE is 200 you have a winner
if ($aws->http_code == 200)
{
    print_r($res);
}
else
{
	// else you have an issue
    echo "the code was: " . $aws->http_code;   
}



?>





