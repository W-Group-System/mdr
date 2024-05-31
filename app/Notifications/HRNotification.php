<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HRNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $name;
    protected $yearAndMonth;
    protected $department;
    protected $rate;
    public function __construct($name, $yearAndMonth, $department, $rate)
    {
        $this->name = $name;
        $this->yearAndMonth = $yearAndMonth;
        $this->department = $department;
        $this->rate = $rate;
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
                    ->subject('NTE Issuance')
                    ->greeting('Hello Mr./Ms. '.$this->name)
                    ->line('MDR rating of '.$this->department.' for the month of '.date("F Y", strtotime($this->yearAndMonth)).' is '.$this->rate.' for issuance of NTE')
                    ->action('View Penalties', url('/penalties'))
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
