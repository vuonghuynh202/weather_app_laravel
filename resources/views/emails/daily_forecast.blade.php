<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Weather Forecast</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333;">Weather Forecast For {{ $weather['current']['location']['name'] }}</h2>
        <p style="text-align: center; color: #555;">Date: {{ $weather['current']['current']['last_updated'] }}</p>

        <div style="text-align: center;">
            <img src="{{ $weather['current']['current']['condition']['icon'] }}" style="width: 100px; height: 100px;">
            <p style="color: #555; font-size: 18px;">{{ $weather['current']['current']['condition']['text'] }}</p>
        </div>

        <p style="font-size: 16px; color: #333;">
            <strong>Temperature:</strong> {{ $weather['current']['current']['temp_c'] }}°C<br>
            <strong>Wind:</strong> {{ $weather['current']['current']['wind_kph'] }} km/h<br>
            <strong>Humidity:</strong> {{ $weather['current']['current']['humidity'] }}%<br>
        </p>

        <h3 style="color: #333;">Forecast for the next 10 days:</h3>

        @foreach($weather['forecast']['forecast']['forecastday'] as $day)
        <div style="border-bottom: 1px solid #ddd; padding: 10px 0;">
            <h4 style="margin: 0; color: #333;">{{ $day['date'] }}</h4>
            <p style="color: #555;">
                <strong>Nhiệt độ:</strong> {{ $day['day']['avgtemp_c'] }}°C<br>
                <strong>Wind:</strong> {{ $day['day']['maxwind_kph'] }} km/h<br>
                <strong>Humidity:</strong> {{ $day['day']['avghumidity'] }}%<br>
                <img src="{{ $day['day']['condition']['icon'] }}" style="width: 50px; height: 50px;">
                <span>{{ $day['day']['condition']['text'] }}</span>
            </p>
        </div>
        @endforeach

        <p style="text-align: center; color: #888;">You received this email because you subscribe for daily weather forecasts.</p>
        <a href="{{ route('unsubscribe', ['email' => $subscriber->email]) }}" style="color: #ff0000; text-decoration: none;">Unsubscribe</a>
    </div>
</body>

</html>