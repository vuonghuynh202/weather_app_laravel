@extends('partials.master')

@section('content')
<div class="header">
    <a href="/" class="logo">
        <div class="logo-img">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </div>
        <h1 class="p-4 text-center text-white header-text">Weather Dashboard</h1>
    </a>
</div>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <form method="GET" id="form-search">
                <div class="form-group mb-4">
                    <label for="search">Enter a City Name</label>
                    <input type="text" name="location" class="form-control" id="search" required placeholder="E.g., Tokyo, Paris, Los Angeles">
                </div>
                <button type="submit" class="search-btn">
                    <div class="search-value">
                        Search
                    </div>
                </button>
            </form>
            <div class="devider">
                <span>or</span>
            </div>
            <button type="submit" id="use-current-location-btn" class="search-btn bg-secondary">Use Current Location</button>
        </div>
        <div class="col-lg-8">
            <div class="weather-detail p-4" id="current-weather">

            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-lg-12">
            <h3 class="mt-4 mb-3">Forecast</h3>
            <div class="row" id="forecast-list">

            </div>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <button class="load-more-btn">Show More</button>
        </div>
    </div>
    <div class="row mb-5 form-subcribe">
        <div class="col-lg-8">
            <form method="POST">
                @csrf
                <h3 class="text-center mb-4">Subscribe To Our Weather Forecast</h3>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            required 
                            placeholder="Enter your email" 
                            value="{{ old('email') }}">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" 
                    class="form-control @error('location') is-invalid @enderror"
                    id="location" 
                    name="location" 
                    required 
                    placeholder="Enter your city name" 
                    value="{{ old('location') }}">
                    @error('location')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" id="subscribe-btn" class="btn btn-secondary">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-12">

            <div class="dropdown">
                <a href="" type="button" class="dropdown-toggle history-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    History
                </a>
                <div class="dropdown-menu py-1" aria-labelledby="dropdownMenuButton">
                    <a href="" id="clear-history-btn" class="dropdown-item py-2">Clear History</a>
                </div>
            </div>

            <div class="row mt-4" id="history-weather">

            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendors/jQuery/jquery-3.7.1.min.js') }}"></script>
<script>
    let isExpanded = false; //Indicates whether the weather forecast is expanded or collapsed
    let currentCity = 'Hanoi' //The name of the currently displayed location


    //function display weather forecast data
    function displayData(location, current, forecast) {
        $('#current-weather').html(`
            <div class="weather-info">
                <h2 class="mb-4 text-white">${location.name} (${current.last_updated.split(' ')[0]})</h2>
                <span class="d-block text-white mb-3">Temperature: ${current.temp_c}°C</span>
                <span class="d-block text-white mb-3">Wind: ${current.wind_kph}km/h</span>
                <span class="d-block text-white mb-3">Humidity: ${current.humidity}%</span>
            </div>
            <div class="weather-image text-center">
                <div class="image">
                    <img src="${current.condition.icon}" alt="">
                </div>
                <p class="d-block text-white weather-name">${current.condition.text}</p>
            </div>
        `);

        let forecastList = '';
        forecast.forEach(function(item) {
            forecastList += `
                <div class="col-lg-3 mb-3">
                    <div class="weather-item bg-secondary py-3 px-4 rounded">
                        <h5 class="mb-3 text-center text-white">${item.date}</h5>
                        <div class="weather-list-img mb-3">
                            <img src="${item.day.condition.icon}" alt="">
                        </div>
                        <span class="d-block text-white mb-2">Temperature: ${item.day.avgtemp_c}°C</span>
                        <span class="d-block text-white mb-2">Wind: ${item.day.maxwind_kph}km/h</span>
                        <span class="d-block text-white">Humidity: ${item.day.avghumidity}%</span>
                    </div>
                </div>
            `;
        });
        $('#forecast-list').html(forecastList);
    }


    //ajax to get weather forcecast data
    function fetchWeather(location = 'Hanoi', forecastDays = 5) {
        $.ajax({
            url: `/weather`,
            method: 'GET',
            data: {
                location: location
            },
            success: function(res) {
                if (res.success) {
                    let location = res.current.location;
                    let current = res.current.current;
                    let forecast = res.forecast.forecast.forecastday;
                    let history = res.history.reverse();

                    displayData(location, current, forecast.slice(1, forecastDays));
                    hideLoading();

                    fetchHistoryWeather(history);

                } else {
                    hideLoading();
                    showToast('error', `City "${location}" not found!`)
                }
            },
            error: function() {
                hideLoading();
                showToast('error', `City "${location}" not found!`)
            }
        });
    }


    //show default location weather
    $(document).ready(function() {
        showLoading();
        fetchWeather(currentCity);
    });


    //search location
    $('.search-btn').on('click', function(event) {
        event.preventDefault();
        let city = $('#search').val();
        if (city) {
            showLoading();
            currentCity = city;
            fetchWeather(currentCity);
        }
    });


    //Get user's current location
    $('#use-current-location-btn').on('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;

                showLoading();
                reverseGeocode(latitude, longitude);

            }, function() {
                showToast('error', `Unable to retrieve your location. Please try again.`)
            });
        }
    });


    //Convert latitude and longitude coordinates to a location name
    function reverseGeocode(lat, lon) {
        $.ajax({
            url: `https://api.opencagedata.com/geocode/v1/json?language=en`,
            method: 'GET',
            data: {
                key: '92251e60b3714ee2bb501b89343418a5',
                q: `${lat},${lon}`
            },
            success: function(res) {
                if (res.results.length > 0) {

                    let city = res.results[0].components.city || res.results[0].components.town || res.results[0].components.village;

                    currentCity = city;
                    fetchWeather(currentCity);
                    hideLoading();
                } else {
                    showToast('error', `City not found for the current location.`);
                }
            },
            error: function() {
                showToast('error', `Error retrieving location data.`);
            }
        });
    }


    //show more/less forecast
    $('.load-more-btn').on('click', function() {
        showLoading();
        if (isExpanded) {
            fetchWeather(currentCity, 5);
            $(this).text('Show More');
        } else {
            fetchWeather(currentCity, 10);
            $(this).text('Show Less');
        }
        isExpanded = !isExpanded;
    });


    //subscribe
    $('#subscribe-btn').on('click', function(ev) {
        ev.preventDefault();
        showLoading();
        $.ajax({
            url: '/subscribe',
            type: 'POST',
            data: {
                email: $('#email').val(),
                location: $('#location').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    hideLoading();
                    showToast('success', res.message);
                } else { 
                    hideLoading();
                    showToast('error', 'Error, please try again.');
                }
            },
            error: function(xhr) {
                hideLoading();
                showToast('error', 'Error, please try again.');
            }
        })
    })


    //show history
    function fetchHistoryWeather(history) {
        let historyContainer = $('#history-weather');
        historyContainer.empty();

        history.forEach(function(city) {
            $.ajax({
                url: `/weather`,
                method: 'GET',
                data: {
                    location: city
                },
                success: function(res) {
                    if (res.success) {
                        let location = res.current.location;
                        let current = res.current.current;
                        let forecast = res.forecast.forecast.forecastday;

                        historyContainer.append(`
                            <div class="col-lg-6 mb-3">
                                <div class="weather-detail p-4">
                                    <div class="weather-info">
                                        <h4 class="mb-4 text-white">${location.name} (${current.last_updated.split(' ')[0]})</h4>
                                        <span class="d-block text-white mb-3">Temperature: ${current.temp_c}°C</span>
                                        <span class="d-block text-white mb-3">Wind: ${current.wind_kph}km/h</span>
                                        <span class="d-block text-white mb-3">Humidity: ${current.humidity}%</span>
                                        <a href="/history/${location.name}">View forecast &rarr;</a>
                                    </div>
                                    <div class="weather-image text-center">
                                        <div class="image">
                                            <img src="${current.condition.icon}" alt="">
                                        </div>
                                        <p class="d-block text-white weather-name">${current.condition.text}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                }
            })
        });
    }


    //clear history
    $('#clear-history-btn').click(function(ev) {
        ev.preventDefault();

        $.ajax({
            url: '/weather/clear-history',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    showToast('success', res.message);
                    $('#history-weather').empty();
                    $('#clear-history-btn').remove();
                }
            },
            error: function() {
                showToast('error', 'Error, please try again.');
            }
        });
    });
</script>
@endsection