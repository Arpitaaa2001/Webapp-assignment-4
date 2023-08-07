<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Weather App</h1>
        <form action="" method="GET">
            <input type="text" name="location" placeholder="Enter a location">
            <button type="submit">Search</button>
        </form>
        <?php
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
            $apiKey = 'e0816140a2197fd99215952e5b8402e1';
            $url = "https://api.openweathermap.org/data/2.5/forecast?q=$location&appid=$apiKey&units=metric";
            $response = file_get_contents($url);
            $data = json_decode($response);

            if ($data) {
                $city = $data->city->name;
                $country = $data->city->country;

                // Group the forecast data by date
                $grouped_data = [];
                foreach ($data->list as $forecast) {
                    $date = date('Y-m-d', $forecast->dt);
                    $grouped_data[$date][] = $forecast;
                }
        ?>
                <div class="weather-data">
                    <?php
                    foreach ($grouped_data as $date => $daily_forecasts) {
                        $first_forecast = $daily_forecasts[0]; // Get the first forecast of the day
                        $icon = $first_forecast->weather[0]->icon;
                        $description = $first_forecast->weather[0]->description;
                        $temp = $first_forecast->main->temp;
                        $humidity = $first_forecast->main->humidity;
                        $pressure = $first_forecast->main->pressure;
                    ?>
                        <div class="card mb-4" style="border-radius: 25px;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-around text-center mb-4 pb-3 pt-2">
                                    <div class="flex-column">
                                        <h2 class="h2"><?php echo $city; ?>, <?php echo $country; ?></h2>
                                        <p class="small"><?php echo date('d M Y', strtotime($date)); ?></p>
                                    </div>
                                    <div class="flex-column">
                                        <img src="https://openweathermap.org/img/wn/<?php echo $icon; ?>.png" alt="Weather icon">
                                        <p class="small"><?php echo $description; ?></p>
                                        <p class="small"><strong>Temperature:</strong> <?php echo $temp; ?>°C</p>
                                        <p class="small"><strong>Humidity:</strong> <?php echo $humidity; ?>%</p>
                                        <p class="small"><strong>Pressure:</strong> <?php echo $pressure; ?> hPa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
        <?php
            } else {
                echo "<p>Location not found.</p>";
            }
        }
        ?>
    </div>
</body>

</html>