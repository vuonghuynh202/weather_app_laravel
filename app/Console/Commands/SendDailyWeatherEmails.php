<?php

namespace App\Console\Commands;

use App\Models\WeatherSubscriber;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyWeatherEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-weather-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily weather forecast emails to subscribers';

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscribers = WeatherSubscriber::where('is_confirmed', true)->get();

        foreach ($subscribers as $subscriber) {
            $weatherData = $this->weatherService->getWeather($subscriber->location);

            Mail::send('emails.daily_forecast', ['weather' => $weatherData, 'subscriber' => $subscriber], function ($message) use ($subscriber) {
                $message->to($subscriber->email)->subject('Daily weather forecast for ' . $subscriber->location);
            });
        }
    }
}
