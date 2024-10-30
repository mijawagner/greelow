<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Notifications\Channels\LogSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class NewCommentNotification extends Notification
{
    use Queueable;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Defines the channels
     * @param object $notifiable
     * @return array
    */
    public function via(object $notifiable): array
    {
        return ['mail', LogSmsChannel::class];
    }

    /**
     * Send the email to the Admin
     * @param object $notifiable
     * @return MailMessage
    */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('A new comment has been posted.')
                    ->action('View Comment', url('/api/comments/'.$this->comment->id))
                    ->line('Thank you!');
    }

    /**
     * Returns message information for Sms
     * @param object $notifiable
     * @return string
    */
    public function toSms(object $notifiable): string
    {
        return "New comment by {$this->comment->author}: {$this->comment->text}";
    }


}
