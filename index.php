<?php
// Allow all origins
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Check if a 'url' query parameter is provided
if (!isset($_GET['url'])) {
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

// Get the URL from the query parameter
$url = $_GET['url'];

// Fetch the content from the URL
$xmlContent = file_get_contents($url);

// Check if the content is XML
if (strpos($xmlContent, '<?xml') === false) {
    echo json_encode(['error' => 'The provided URL does not contain XML content']);
    exit;
}

// Load the XML content
$xml = simplexml_load_string($xmlContent);

// Check if the XML is valid
if ($xml === false) {
    echo json_encode(['error' => 'Invalid XML content']);
    exit;
}

// Convert the XML to JSON
$json = json_encode($xml);

// Output the JSON content
echo $json;
