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
                    <h2 class="footer-time fs-1 fw-semibold mb-1">9:30 <span class="time-sub fst-normal fs-3">am</span></h2>
                    <p class="footer-day fs-5 mb-1 fw-light">Sunday</p>
                    <p class="footer-date fs-5 mb-0 fw-light">April 20, 2025</p>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end">
                    <div class="weather text-white">
                        <!-- <span id="weather-info" class="text-white">
                            <i class="fas fa-cloud"></i> Loading weather...
                        </span> -->
                        <h2 class="weather-current fw-light fs-2 mb-1">
                            21
                            <span class="fs-4 top-text">0</span>
                            <svg width="42" viewBox="0 0 50 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M40.226 22.8936C45.9674 21.7796 49.3937 17.1411 49.3937 14.0914C49.3937 13.3427 49.0605 12.8131 48.4492 12.8131C47.5046 12.8131 46.1341 13.9636 43.5411 13.9636C39.0591 13.9819 36.2439 11.2974 36.2439 7.26156C36.2439 4.77798 37.5034 2.98832 37.5034 1.98392C37.5034 1.39955 37.0958 1.01605 36.3549 1.03432C32.9842 1.16215 28.502 4.88754 27.4649 9.83646C28.0946 10.4208 28.6873 11.1878 29.1689 12.1922C35.2808 12.6123 39.9851 17.1959 40.226 22.8936ZM7.90673 32.6637H28.4465C33.7436 32.6637 37.8182 28.6826 37.8182 23.5328C37.8182 18.3464 33.6139 14.4749 27.9836 14.4567C25.8722 10.4939 22.0753 7.95551 17.2784 7.95551C11.1294 7.95551 5.9435 12.6123 5.38786 18.7116C2.16521 19.6247 0.109375 22.3091 0.109375 25.5963C0.109375 29.7964 3.27647 32.6637 7.90673 32.6637Z" fill="white" />
                            </svg>
                        </h2>
                        <p class="weater-status fs-5 mb-1 fw-light">Partly Cloudy</p>
                        <p class="weather-direction fs-5 mb-0 fw-light">H:29° L:15°</p>
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