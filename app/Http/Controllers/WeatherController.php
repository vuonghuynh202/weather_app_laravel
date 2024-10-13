<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Session;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    
    public function index() {
        return view('index');
    }

    public function getWeather(Request $request)
    {
        $location = $request->input('location');
        $isHistory = $request->input('is_history', false);
        $weatherData = $this->weatherService->getWeather($location);

        if(!$isHistory) {
            $history = Session::get('weather_history', []);
            if (!in_array($location, $history)) {
                $history[] = $location;
                Session::put('weather_history', $history);
            }
        }

        return response()->json([
            'success' => true,
            'current' => $weatherData['current'],
            'forecast' => $weatherData['forecast'],
            'history' => Session::get('weather_history', [])
        ]);
    }

    public function clearHistory()
    {
        Session::forget('weather_history');
        return response()->json([
            'success' => true, 
            'message' => 'Search history deleted.'
        ]);
    }

    public function historyDetails($location) {
        return view('historyDetails', compact('location'));
    }
}
