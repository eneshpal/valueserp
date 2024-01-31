<?php

function getSearchResults($query){
	
	# set up the request parameters
$queryString = http_build_query([
  'api_key' => 'C1CD05DBC9EE4D428E86290789947F01',
  'q' => $query
]);

# make the http GET request to VALUE SERP
$ch = curl_init(sprintf('%s?%s', 'https://api.valueserp.com/search', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
# the following options are required if you're using an outdated OpenSSL version
# more details: https://www.openssl.org/blog/blog/2021/09/13/LetsEncryptRootCertExpire/
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 180);

$api_result = curl_exec($ch);
return $searchResults = json_decode($api_result, true);
//echo $data;
curl_close($ch);
}



// Replace 'YOUR_API_KEY' with your actual ValueSERP API key
$apiKey = 'C1CD05DBC9EE4D428E86290789947F01';
//$valueSERP = new ValueSERPAPI($apiKey);

// Retrieve search results for the query
$query = $_POST['query'];
$searchResults = getSearchResults($query);

// Generate CSV file
$csvFileName = 'search_results.csv';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

$output = fopen('php://output', 'w');
// Add CSV header row
fputcsv($output, ['Title', 'Link', 'Snippet']);

// Add search results to CSV
foreach ($searchResults['organic_results'] as $result) {
    fputcsv($output, [$result['title'], $result['link'], $result['snippet']]);
}

fclose($output);

?>
