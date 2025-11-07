$(document).ready(function () {
    $('#weather-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const city = $('#city').val().trim();

        const url = `/weather-info?city=${encodeURIComponent(city)}`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error) {
                    // display error message
                    $('#weather-result').addClass('d-none');
                    $('#error-msg').removeClass('d-none');
                    $('#error-msg').addClass('d-block');
                    $('#error-msg-txt').text(data.errorMessage);
                    return;
                }

                $('#error-msg').addClass('d-none');
                // construct weather icon url
                var iconUrl = "http://openweathermap.org/img/wn/" + data.iconCode + "@2x.png";

                // display fetched weather data
                $('#city-name').text(data.city);
                $('#weather-description').text(data.weatherDescription);
                $('#weather-icon-desc').attr("src", iconUrl)
                $('#temperature').text(data.temperature);
                $('#humidity').text(data.humidity);
                $('#wind-speed').text(data.wind_speed);
                $('#wind-dir').text(data.wind_dir);
                $('#weather-result').removeClass('d-none');
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#weather-result').addClass('d-none');
                $('#error-msg').addClass('d-block');
                $('#error-msg-txt').text(error);
            }
        });
    });
});

