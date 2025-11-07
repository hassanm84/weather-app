<?php include __DIR__ . '/layouts/header.php'; ?>

<!-- about Section -->
<div class="card mt-4 mb-4 shadow-sm border-0">
    <div class="card-body bg-light">
        <h5 class="card-title mb-3">
            <i class="bi bi-info-circle text-primary me-2"></i> About this application
        </h5>
        <p class="card-text text-muted">This is a pretty basic
            demo project developed as part of a technical assessment for a developer position.</p>
        <p class="card-text text-muted">
            The <strong>Weather Information Application</strong> provides real-time
            weather information for cities across the UK. Simply enter a city/town name, and the app
            will fetch current conditions i.e. temperature, humidity, wind speed and direction using the Open Weather Map API. Owing to 
            its responsive design, you can access it on any mobile device. 
        </p>
    </div>
</div>

<!-- form -->
<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <form id="weather-form" class="card shadow-sm p-4">
            <div class="mb-3 mt-3">
                <label for="city" class="form-label">Please enter name of a UK city</label>
                <div class="input-group input-group-focus">
                    <span id="icon-addon" class=" input-group-text bg-light"><i class="bi bi-geo-alt text-primary"></i></span>
                    <input type="text" class="form-control" id="city" placeholder="Town / City" aria-describedby="cityHelp" required>
                </div>
                <small class="text-muted">E.g. London, St Albans, Oxford.</small>
                <div class="mt-3">
                    <button type="submit" class="btn btn-lg btn-primary w-100">Get Weather Info</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- weather info section -->
<div class="row justify-content-center mt-4">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">

        <?php include __DIR__ . '/layouts/weather_error_msg.php'; ?>

        <div id="weather-result" class="card d-none shadow-sm">
            <div class="card-body text-left">
                <h1 class="card-title" id="city-name"></h1>
                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <!-- current temperature -->
                    <h4 class="card-text mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-thermometer-half fs-1 weather-icon-temp"></i>
                        <strong><span id="temperature"></span> <?= htmlspecialchars($temperatureUnit) ?></strong>
                    </h4>
                    <div id="weather-divider" class="border-start mx-2"></div>
                    <!-- weather description -->
                    <h4 class="card-text mb-0 d-flex align-items-center gap-2">
                        <strong><span id="weather-description"></span></strong>
                        <img id="weather-icon-desc" alt="Weather Icon">
                    </h4>
                </div>
                <!-- humidity -->
                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <h4 class="card-text mb-0 d-flex align-items-center gap-2">
                        <strong><i class="bi bi-droplet-fill fs-1 weather-icon-humidity"></i> Humidity: <span id="humidity"></span>%</strong>
                    </h4>
                </div>

                <!-- wind info-->
                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <h4 class="card-text mb-0 d-flex align-items-center gap-2">
                        <strong><i class="bi bi-wind fs-1 weather-icon-wind-speed"></i> Wind Speed: <span id="wind-speed"></span> <?= htmlspecialchars($windUnit) ?></strong>
                    </h4>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <h4 class="card-text mb-0 d-flex align-items-center gap-2">
                        <strong><i class="bi bi-compass fs-1 weather-icon-wind-dir"></i> Wind Direction: <span id="wind-dir"></span>&deg</strong>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<?php include __DIR__ . '/layouts/footer.php'; ?>