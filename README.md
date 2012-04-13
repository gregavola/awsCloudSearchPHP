# awsCloudSearch PHP API Wrapper

This library is written to make calls against the AWS Cloud Search API.<br />

# Requirements
PHP 5+<br />
CURL<br />

# Getting Started
Follow the instructions in <code>example.php</code> for a detailed example of <code>search</code> method. Follow the instructions on the website (http://docs.amazonwebservices.com/cloudsearch/latest/developerguide/APIReq.html)

<br />It's easy to make a call to AWS Cloud Search. Just include the <code> awsCloudSearch.php </code> your script and use the following examples below.

<pre>
$myAWS = new awsCloudSearch(domain, domain_id);
$res = $myAWS->search(TERM, PARAM);
</pre>

If you want to add a document, make sure the PARAM is in an array and formatted to the Search Data Format (SDF) (http://docs.amazonwebservices.com/cloudsearch/latest/developerguide/GettingStartedSendData.html)

<pre>
$myAWS = new awsCloudSearch(domain, domain_id);
$res = $myAWS->document(TYPE,TERM, PARAM);
</pre>

Remember <code>TYPE</code> is either <code>add</code> or <code>delete</code>.

# To Do
Error Handling

# Getting Help
If you need help or have questions, please contact Greg Avola on Twitter at http://twitter.com/gregavola