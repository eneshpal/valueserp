<?php


// Replace 'YOUR_API_KEY' with your actual ValueSERP API key
$apiKey = 'C1CD05DBC9EE4D428E86290789947F01';

// Initialize variables
$query = '';
$searchResults = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$query = $_POST['query'];
	
    $queryString = http_build_query([
       'api_key' => 'C1CD05DBC9EE4D428E86290789947F01',
       'q' => $query
    ]);
	
	//echo "<pre>";
	//print_r($queryString);
	//die();
	
    # make the http GET request to VALUE SERP
	$ch = curl_init(sprintf('%s?%s', 'https://api.valueserp.com/search', $queryString));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	# the following options are required if you're using an outdated OpenSSL version
	# more details: https://www.openssl.org/blog/blog/2021/09/13/LetsEncryptRootCertExpire/
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 180);

	$api_result    = curl_exec($ch);
	$searchResults = json_decode($api_result, true);
	//echo "<pre>";
	//print_r($searchResults['organic_results']);
	//die();
	//echo $data;
	curl_close($ch);
}

//echo "<pre>";
//print_r($searchResults);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ValueSERP Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>ValueSERP Search</h2>

    <form method="post">
        <label for="query">Enter your search query:</label>
        <input type="text" id="query" name="query" value="<?php echo htmlspecialchars($query); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
        <h3>Search Results for "<?php echo htmlspecialchars($query); ?>"</h3>
        <table>
            <thead>
            <tr>
                <th>Title</th>
                <th>URL</th>
                <th>Snippet</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($searchResults['organic_results'] as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['title']); ?></td>
                    <td><?php echo htmlspecialchars($result['link']); ?></td>
                    <td><?php echo htmlspecialchars($result['snippet']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Download CSV button -->
        <form method="post" action="download.php">
            <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
            <button type="submit">Download CSV</button>
        </form>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>No results found for "<?php echo htmlspecialchars($query); ?>"</p>
    <?php endif; ?>
</div>

</body>
</html>
