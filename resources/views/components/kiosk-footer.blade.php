<!-- Kiosk Footer -->
<div class="kiosk-footer py-5">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <!-- Time and Date -->
                <div class="datetime">
                    <span id="current-time" class="text-white"></span>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end">
                    <div class="weather">
                        <span id="weather-info" class="text-white">
                            <i class="fas fa-cloud"></i> Loading weather...
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('current-time').textContent = now.toLocaleString('en-US', options);
    }

    // Update time every second
    setInterval(updateDateTime, 1000);
    updateDateTime(); // Initial call

    // Fetch weather data
    async function fetchWeather() {
        try {
            // Using browser's geolocation API to get user's location
            navigator.geolocation.getCurrentPosition(async (position) => {
                const {
                    latitude,
                    longitude
                } = position.coords;
                const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=YOUR_API_KEY`);
                const data = await response.json();

                if (data.main && data.weather) {
                    const temp = Math.round(data.main.temp);
                    const description = data.weather[0].description;
                    const high = Math.round(data.main.temp_max);
                    const low = Math.round(data.main.temp_min);

                    document.getElementById('weather-info').innerHTML =
                        `<i class="fas fa-temperature-high"></i> ${temp}°C, ${description}, H:${high}° L:${low}°`;
                }
            }, () => {
                // Error callback - when user denies location access
                document.getElementById('weather-info').innerHTML =
                    '<i class="fas fa-exclamation-circle"></i> Weather unavailable';
            });
        } catch (error) {
            console.error('Error fetching weather:', error);
            document.getElementById('weather-info').innerHTML =
                '<i class="fas fa-exclamation-circle"></i> Weather unavailable';
        }
    }

    // Fetch weather initially and then every 5 minutes
    fetchWeather();
    setInterval(fetchWeather, 5 * 60 * 1000);
</script>
@endpush