<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovedNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $mdrOwner;
    private $year;
    private $month;
    
    public function __construct($mdrOwner, $year, $month)
    {
        $this->mdrOwner = $mdrOwner->name;
        $this->year = $year;
        $this->month = $month;
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
                    ->greeting("Hello Mr./Ms. ".' '. $this->mdrOwner)
                    ->line('We would like to inform you that your MDR in ' . date('F Y', strtotime($this->year . '-' . $this->month . '-01')) . ' is approved' )
                    ->action('Click the button to see your MDR', url('mdr'))
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
