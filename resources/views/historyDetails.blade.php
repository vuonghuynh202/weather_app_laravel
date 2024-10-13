@extends('partials.master')

@section('content')
<div class="header">
    <a href="/" class="logo">
        <div class="logo-img">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </div>
        <h1 class="p-4 text-center text-white">Weather Dashboard</h1>
    </a>
</div>
<input type="hidden" id="location" value="{{ $location }}">
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="weather-detail p-4" id="current-weather">
                
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-lg-12">
            <h3 class="mt-5 mb-3">Forecast</h3>
            <div class="row" id="forecast-list">

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendors/jQuery/jquery-3.7.1.min.js') }}"></script>
<script>
   

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

    $(document).ready(function() {
        showLoading();
        fetchWeather($('#location').val(), false);
    });


    //ajax to get weather forcecast data
    function fetchWeather(location, isHistory = false) {
        $.ajax({
            url: `/weather`,
            method: 'GET',
            data: {
                location: location,
                is_history: isHistory
            },
            success: function(res) {
                if (res.success) {
                    let location = res.current.location;
                    let current = res.current.current;
                    let forecast = res.forecast.forecast.forecastday.slice(1, 10);

                    displayData(location, current, forecast);
                    hideLoading();
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
</script>
@endsection