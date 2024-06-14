# Substack XML to JSON Proxy Converter

This PHP script fetches an XML feed from a Substack publication URL, converts it into JSON format, and acts as a proxy to serve this JSON data. It includes caching mechanisms to improve performance by reducing frequent requests to the Substack servers.

## Features

- **XML to JSON Conversion:** Converts XML data fetched from a Substack publication's RSS feed into JSON format.
- **Caching:** Stores the converted JSON data in a cache file (`cache.json`) to minimize fetching and processing time on subsequent requests.
- **Proxy Server:** Acts as a proxy server to fetch and serve JSON data, reducing load on the Substack server and improving response times.
- **Environment Variable Configuration:** Uses a `.env` file to manage configuration settings, including the Substack publication URL.

## Setup

### Requirements

- PHP (>= 5.4)
- Web server (Apache, Nginx, etc.)

### Installation Steps

1. **Clone the Repository:**
git clone https://github.com/your/repository.git
cd repository

2. **Install Dependencies:**
No external dependencies need to be installed.

3. **Set Environment Variables:**
Create a `.env` file in the project root with the following content:
URL=https://your-substack-publication-url.com/feed

Replace `https://your-substack-publication-url.com/feed` with your actual Substack publication RSS feed URL.

4. **Configure Web Server:**
Ensure your web server is configured to serve PHP files.

## Usage

1. **Access the Proxy:**
Navigate to the script URL on your web server (`http://your-domain.com/path/to/convert.php`). It will fetch the XML data from the Substack feed URL, convert it to JSON, and serve it.

2. **Refreshing Cache:**
The script automatically refreshes the cached JSON data every 6 hours (configurable via `CACHE_DURATION` constant in seconds).

## Notes

- **Error Handling:** The script handles cases where the `.env` file is missing or does not define the required `URL`.
- **Content Type:** The script sets the appropriate `Content-Type` header (`application/json`) for JSON responses.
- **Dependencies:** It uses PHP's built-in functionalities (`file_get_contents`, `SimpleXMLElement`, etc.) without external libraries for simplicity and ease of deployment.

## Contributing

Contributions are welcome! Feel free to fork the repository, make improvements, and submit pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
