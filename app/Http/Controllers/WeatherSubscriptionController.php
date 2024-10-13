<?php

namespace App\Http\Controllers;

use App\Models\WeatherSubscriber;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WeatherSubscriptionController extends Controller
{

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:weather_subscribers,email',
            'location' => 'required|string',
        ]);

        $token = Str::random(32);

        $subscriber = WeatherSubscriber::create([
            'email' => $request->email,
            'location' => $request->location,
            'token' => $token,
        ]);

        $this->sendConfirmationEmail($subscriber);

        return response()->json([
            'success' => true,
            'message' => 'Please check your email to confirm subscription.'
        ]);
    }

    protected function sendConfirmationEmail($subscriber)
    {
        $confirmationUrl = route('confirm.email', ['token' => $subscriber->token]);

        Mail::send('emails.confirmation', ['url' => $confirmationUrl], function ($message) use ($subscriber) {
            $message->to($subscriber->email)->subject('Confirm subscription to receive weather forecasts');
        });
    }

    public function confirmEmail($token)
    {
        $subscriber = WeatherSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid!'
            ], 400);
        }

        $subscriber->update(['is_confirmed' => true, 'token' => null]);

        return redirect('/');
    }


    public function unsubscribe($email)
    {
        $subscriber = WeatherSubscriber::where('email', $email)->first();
    
        if (!$subscriber) {
            return redirect('/')->with('error', 'Email does not exist!');
        }

        $subscriber->delete();
    
        return redirect('/')->with('success', 'You have been unsubscribed successfully!');
    }  
}
