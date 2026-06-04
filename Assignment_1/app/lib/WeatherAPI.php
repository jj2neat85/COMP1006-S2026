<?php

class WeatherAPI
{
    private $baseUrl;
    private $apiKey;

    // Constructor to initialize the API base URL and key
    public function __construct($baseUrl, $apiKey)
    {
        // Store the base URL and API key for use in API requests
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /*
        Private helper method to perform an HTTP GET request.
        Returns the raw response body as a string, or null on failure.
    */
    private function get(string $url): ?string
    {
        // Initialize cURL and set options for a simple GET request
        $ch = curl_init($url);

        // Set options: return response as string, timeout, and disable SSL verification (SSL was causing issues on my laptop)
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        // Execute the request and check for errors
        $raw = curl_exec($ch);

        // The request is successful if there are no cURL errors and we receive a 200 OK status code
        $ok  = !curl_error($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;

        // Close the cURL handle to free resources (Good practice)
        curl_close($ch);

        // Return the raw response if successful, or null on failure
        return ($ok && $raw) ? $raw : null;
    }

    /*
        Public method to geocode a city name into coordinates.
        Returns an array with 'name', 'lat', and 'lon' keys, or null on failure.
    */
    public function geocode(string $city): ?array // ?array is shorthand for array|null meaning it can either return an array or null
    {
        // Google Maps Geocoding API endpoint
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . rawurlencode($city) . '&key=' . $this->apiKey;

        // Perform the GET request and check for a valid response
        $raw = $this->get($url);
        if (!$raw) return null;

        // Decode the JSON and extract the first result if available
        $data = json_decode($raw, true);
        if (!$data || $data['status'] !== 'OK') return null;

        // Extract the formatted address and coordinates from the first result
        $r = $data['results'][0];
        return [
            'name' => $r['formatted_address'],
            'lat' => $r['geometry']['location']['lat'],
            'lon' => $r['geometry']['location']['lng'],
        ];
    }

    /*
        Public method to fetch current weather conditions for given coordinates.
        Returns the decoded response array, or null on failure.
    */
    public function currentConditions(float $lat, float $lon): ?array
    {
        // Google Weather API endpoint for current conditions, using lat and long
        $url = "{$this->baseUrl}/currentConditions:lookup?key={$this->apiKey}&location.latitude={$lat}&location.longitude={$lon}";

        // Perform the GET request and decode the JSON response, or return null on failure
        $raw = $this->get($url);
        return $raw ? json_decode($raw, true) : null;
    }

    /*
        Public method to fetch a multi-day forecast (defaults to 7 days) for the given coordinates.
        Returns the decoded response array, or null on failure.
    */
    public function forecast(float $lat, float $lon, int $days = 7): ?array
    {
        // Google Weather API endpoint for multi-day forecast, using lat, long, and number of days
        $url = "{$this->baseUrl}/forecast/days:lookup?key={$this->apiKey}&location.latitude={$lat}&location.longitude={$lon}&days={$days}";

        // Perform the GET request and decode the JSON response, or return null on failure
        $raw = $this->get($url);
        return $raw ? json_decode($raw, true) : null;
    }
}