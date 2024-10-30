<?php

// app/Notifications/Channels/LogSmsChannel.php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Notifications\Channel;

class LogSmsChannel
{
    /**
     * Implements Sms using simulating service logging
     * @param object $notifiable
     * @param Notification $notification     *
     * @return JsonResponse
    */
    public function send(object $notifiable, Notification $notification)
    {
            // Get the message content from the notification
            $message = $notification->toSms($notifiable);

            // Log the simulated SMS message
            Log::info("SMS Notification: $message");
        }
}
