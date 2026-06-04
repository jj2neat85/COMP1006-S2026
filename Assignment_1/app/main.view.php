<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Head data -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo !empty($geo['name']) ? htmlspecialchars($geo['name']) . ' — Weather' : 'Weather Dashboard'; ?></title>
        <link rel="stylesheet" href="app/styles/main.view.css">
    </head>
    <body>
        <!-- Main app container -->
        <main class="app-container">

            <!-- Search form -->
            <form class="search-form" method="GET" role="search">
                <input type="search" name="city" placeholder="Search for a city…" value="<?php echo htmlspecialchars($city ?? ''); ?>" autocomplete="off" required>
                <button type="submit">Search</button>
            </form>

            <!-- Error notice (if applicable) -->
            <?php if (!empty($error)) { ?>
                <p class="error-notice" role="alert"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <!-- Current weather -->
            <!-- Only show current weather if we have data -->
            <?php if (!empty($weather)) {
                $currentTemp = round($weather['temperature']['degrees'] ?? 0);
                $currentFeels = round($weather['feelsLikeTemperature']['degrees'] ?? 0);
                $currentDesc = $weather['weatherCondition']['description']['text'] ?? 'Showers possible';
                $todayHi = round($forecast['forecastDays'][0]['maxTemperature']['degrees'] ?? $currentTemp);
                $todayLo = round($forecast['forecastDays'][0]['minTemperature']['degrees'] ?? $currentTemp);
                $degreeSymbol = '*C';
            ?>

                <!-- City name -->
                <header class="location-header">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <?php echo htmlspecialchars($geo['name'] ?? 'Unknown City'); ?>
                </header>

                <!-- Temp, high/low, feels like -->
                <section class="current-weather">
                    <p class="current-temp"><?php echo $currentTemp . $degreeSymbol; ?></p>
                    <p class="current-details">
                        <span>↑ <?php echo $todayHi . $degreeSymbol; ?> / ↓ <?php echo $todayLo . $degreeSymbol; ?></span>
                        <span>Feels like <?php echo $currentFeels . $degreeSymbol; ?></span>
                    </p>
                </section>

                <!-- 7-day forecast strip -->
                <!-- Only show forecast if we have data -->
                <?php if (!empty($weeklyForecast)) { ?>
                <section class="glass-card">
                    <p class="card-summary"><?php echo htmlspecialchars($currentDesc); ?>. Low <?php echo $todayLo . $degreeSymbol; ?>.</p>

                    <ol class="forecast-strip" role="list">

                        <!-- Loop through each day in the forecast and display it -->
                        <?php foreach ($weeklyForecast as $singleDay) {
                            // Extract and prepare data for this day
                            $dayCondition = $singleDay['daytimeForecast']['weatherCondition'] ?? [];
                            $dayHi = round($singleDay['maxTemperature']['degrees'] ?? 0);
                            $dayLo = round($singleDay['minTemperature']['degrees'] ?? 0);
                            $dayDate = $singleDay['displayDate'] ?? [];

                            // Format day label, icon URL, and description
                            $dayLabel = date('D', mktime(0, 0, 0, $dayDate['month'] ?? 1, $dayDate['day'] ?? 1, $dayDate['year'] ?? 2026));
                            $dayIcon = ($dayCondition['iconBaseUri'] ?? 'https://maps.gstatic.com/weather/v1/sunny') . '.png';
                            $dayDesc = $dayCondition['description']['text'] ?? '';
                        ?>
                            <li class="forecast-day">
                                <span class="forecast-day__label"><?php echo $dayLabel; ?></span>
                                <img src="<?php echo htmlspecialchars($dayIcon); ?>" alt="<?php echo htmlspecialchars($dayDesc); ?>" class="forecast-day__icon">
                                <span class="forecast-day__temps">
                                    <span class="forecast-day__hi"><?php echo $dayHi . $degreeSymbol; ?></span>
                                    <span class="forecast-day__lo"><?php echo $dayLo . $degreeSymbol; ?></span>
                                </span>
                            </li>
                        <?php } ?>
                    </ol>
                </section>
                <?php } ?>

            <?php } ?>

        </main>

        <!-- Script to redirect to home when search is cleared -->
        <script>
            document.querySelector('.search-form input[type="search"]')
                .addEventListener('search', e => { if (e.target.value === '') window.location.href = '/'; });
        </script>

    </body>
</html>
