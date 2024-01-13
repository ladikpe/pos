<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisteredUserNotification extends Notification
{
    use Queueable;


    private $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Welcome To UGL POS')
                    ->line('Your Account Has Just Been Registered')
                    ->line('Name: '. $this->userData['user_name'])
                    ->line( 'Email: '. $this->userData['email'])
                    ->line('Staff Id: '. $this->userData['staff_id'])
                    ->line('Default Password: '. $this->userData['password'])
                      ->line('Please Change Your Password After First Login');

    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->userData['user_id'],
            'user_name' => $this->userData['user_name'],
            'staff_id' => $this->userData['staff_id']
        ];
    }
}
