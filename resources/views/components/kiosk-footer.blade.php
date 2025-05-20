<!-- Kiosk Footer -->
<div class="kiosk-footer py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6">
                <!-- Time and Date -->
                <!-- <div class="datetime">
                    <span id="current-time" class="text-white"></span>
                </div> -->
                <div class="text-white">
                    <h2 class="footer-time fs-1 fw-semibold mb-1"><span id="footerTime">--:--</span> <span class="time-sub fst-normal fs-3" id="footerAmPm">--</span></h2>
                    <p class="footer-day fs-5 mb-1 fw-light" id="footerDay">--</p>
                    <p class="footer-date fs-5 mb-0 fw-light" id="footerDate">--</p>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end">
                    <div class="weather text-white">
                        <!-- <span id="weather-info" class="text-white">
                            <i class="fas fa-cloud"></i> Loading weather...
                        </span> -->
                        <h2 class="weather-current fw-light fs-2 mb-1">
                            <span id="weather-temp">--</span>
                            <span class="fs-4 top-text">°</span>
                            <span id="weather-icon"></span>
                        </h2>
                        <p class="weather-status fs-5 mb-1 fw-light" id="weather-description">Loading weather...</p>
                        <p class="weather-direction fs-5 mb-0 fw-light" id="weather-high-low">--</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function updateFooterTime() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        document.getElementById('footerTime').textContent = `${hours}:${minutes}`;
        document.getElementById('footerAmPm').textContent = ampm;
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        document.getElementById('footerDay').textContent = days[now.getDay()];
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('footerDate').textContent = now.toLocaleDateString(undefined, options);
    }
    setInterval(updateFooterTime, 1000);
    updateFooterTime();

    // Weather Icons Mapping
    const weatherIcons = {
        '01d': '☀️', // clear sky
        '01n': '🌙', // clear sky night
        '02d': '⛅', // few clouds
        '02n': '☁️', // few clouds night
        '03d': '☁️', // scattered clouds
        '03n': '☁️', // scattered clouds night
        '04d': '☁️', // broken clouds
        '04n': '☁️', // broken clouds night
        '09d': '🌧️', // shower rain
        '09n': '🌧️', // shower rain night
        '10d': '🌦️', // rain
        '10n': '🌧️', // rain night
        '11d': '⛈️', // thunderstorm
        '11n': '⛈️', // thunderstorm night
        '13d': '🌨️', // snow
        '13n': '🌨️', // snow night
        '50d': '🌫️', // mist
        '50n': '🌫️', // mist night
    };

    // Fetch weather data
    async function fetchWeather() {
        try {
            // Using browser's geolocation API to get user's location
            navigator.geolocation.getCurrentPosition(async (position) => {
                const { latitude, longitude } = position.coords;
                
                // Replace YOUR_API_KEY with your actual OpenWeatherMap API key
                const apiKey = "{{ env('OPENWEATHER_API_KEY') }}";
                const response = await fetch(
                    `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=${apiKey}`
                );
                
                if (!response.ok) {
                    throw new Error('Weather API request failed');
                }

                const data = await response.json();

                if (data.main && data.weather) {
                    const temp = Math.round(data.main.temp);
                    const description = data.weather[0].description;
                    const high = Math.round(data.main.temp_max);
                    const low = Math.round(data.main.temp_min);
                    const iconCode = data.weather[0].icon;

                    // Update weather display
                    document.getElementById('weather-temp').textContent = temp;
                    document.getElementById('weather-icon').textContent = weatherIcons[iconCode] || '🌡️';
                    document.getElementById('weather-description').textContent = 
                        description.charAt(0).toUpperCase() + description.slice(1);
                    document.getElementById('weather-high-low').textContent = `H:${high}° L:${low}°`;
                }
            }, (error) => {
                // Error callback - when user denies location access
                document.getElementById('weather-description').textContent = 'Location access denied';
                document.getElementById('weather-temp').textContent = '--';
                document.getElementById('weather-high-low').textContent = 'Weather unavailable';
            });
        } catch (error) {
            console.error('Error fetching weather:', error);
            document.getElementById('weather-description').textContent = 'Weather unavailable';
            document.getElementById('weather-temp').textContent = '--';
            document.getElementById('weather-high-low').textContent = 'Error loading weather';
        }
    }

    // Fetch weather initially and then every 5 minutes
    fetchWeather();
    setInterval(fetchWeather, 5 * 60 * 1000);
</script>
@endpush