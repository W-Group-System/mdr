<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyDeptHead extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $name;
    protected $yearAndMonth;
    public function __construct($name, $yearAndMonth)
    {
        $this->name = $name;
        $this->yearAndMonth = $yearAndMonth;
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
                    ->subject('MDR Approval')
                    ->greeting('Hello Mr./Ms. ' . $this->name)
                    ->line("We would like to inform you that your MDR for the month of " . date('F Y', strtotime($this->yearAndMonth)) . " is ready for your approval")
                    ->action('View MDR', url('/edit_mdr?yearAndMonth='.$this->yearAndMonth))
                    ->line('Thank you for using our application!');
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
