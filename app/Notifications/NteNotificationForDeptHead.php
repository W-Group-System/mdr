<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NteNotificationForDeptHead extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $filePath;
    protected $name;
    protected $yearAndMonth;
    public function __construct($filePath, $name, $yearAndMonth)
    {
        $this->filePath = $filePath;
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
                    ->subject('NTE Issued')
                    ->greeting('Hello Mr./Ms.'.' '.$this->name)
                    ->line('We would like to inform that your department have issued a NTE because your total rating in MDR for the month of '.date('F Y', strtotime($this->yearAndMonth)).' is below 2.99')
                    ->action('View NTE', url('penalties'))
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
