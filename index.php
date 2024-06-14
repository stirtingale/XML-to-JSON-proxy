<?php
// Set the content type to application/json
header('Content-Type: application/json');
// Define the cache file and duration (6 hours in seconds)
define('CACHE_FILE', 'cache.json');
define('CACHE_DURATION', 6 * 60 * 60);
// Load environment variables from .env file
// Define the path to your .env file
$envFilePath = __DIR__ . '/.env';
// Function to load environment variables from .env file
function loadEnv($filePath)
{
    $variables = [];
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip commented lines
            }
            list($key, $value) = explode('=', $line, 2);
            $variables[$key] = $value;
        }
    } else {
        echo json_encode(['error' => 'No .env file']);
        exit;
    }
    return $variables;
}

// Load environment variables
$dotenv = loadEnv($envFilePath);
// Check if 'URL' is defined in the loaded .env variables
if (!isset($dotenv['URL'])) {
    echo json_encode(['error' => 'No URL provided in the .env file']);
    exit;
}

// Function to fetch data from URL
function fetchDataFromUrl($url)
{
    $xml = file_get_contents($url);
    return new SimpleXMLElement($xml);
}

// Check if cached file exists and is fresh
if (file_exists(CACHE_FILE) && time() - filemtime(CACHE_FILE) < CACHE_DURATION) {
    // Use cached data
    $jsonFeed = json_decode(file_get_contents(CACHE_FILE), true);
} else {
    // Fetch the URL from .env
    $url = $dotenv['URL'];
    $feed = fetchDataFromUrl($url);

    // Initialize an empty array to store the JSON feed items
    $jsonFeed = array();

    // Loop through each item in the XML feed
    foreach ($feed->channel->item as $item) {
        // Extract the required fields from the XML item
        $title = (string) $item->title;
        $description = (string) $item->description;
        $link = (string) $item->link;
        $date = (string) $item->pubDate;

        // Check if the item has an enclosure tag with a URL attribute
        if (isset($item->enclosure) && isset($item->enclosure['url'])) {
            $image = (string) $item->enclosure['url'];
        } else {
            $image = ''; // Set image to an empty string if not found
        }

        // Create an associative array for the JSON item
        $jsonItem = array(
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'date' => $date,
            'link' => $link
        );

        // Add the JSON item to the JSON feed array
        $jsonFeed[] = $jsonItem;
    }

    // Save JSON feed to cache file
    file_put_contents(CACHE_FILE, json_encode($jsonFeed));
}

// Set the appropriate content type header for JSON
header('Content-Type: application/json');

// Output the JSON feed
echo json_encode($jsonFeed);
