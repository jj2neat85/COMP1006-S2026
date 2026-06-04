<?php

// Require config and API classes
require_once 'config/config.php';
require_once 'app/lib/WeatherAPI.php';

// Get city from URL, initialize API
$city = trim($_GET['city'] ?? '');
$api  = new WeatherAPI(GOOGLE_WEATHER_BASE_URL, GOOGLE_API_KEY);

// Fetch geo, current weather, and forecast
$geo = $city ? $api->geocode($city) : null;
$weather = $geo ? $api->currentConditions($geo['lat'], $geo['lon']) : null;
$forecast = $weather ? $api->forecast($geo['lat'], $geo['lon']) : null;

// Slice forecast down to 7 days
$weeklyForecast = array_slice($forecast['forecastDays'] ?? [], 0, 7);

require_once 'app/main.view.php';