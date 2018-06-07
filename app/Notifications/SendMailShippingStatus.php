<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendMailShippingStatus extends Notification
{
    use Queueable;

    protected $shipping;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Ada update informasi shipping Anda, dengan Invoice ' . $this->shipping->invoice_no)
                    ->action('Login', org_url('login'))
                    ->line('Silahkan Anda login dan cek shipping Anda.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
