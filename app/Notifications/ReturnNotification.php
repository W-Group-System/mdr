<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnNotification extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $name;
    public $year;
    public $month;
    public $approverName;
    public $departments;
    public $link;

    public function __construct($name, $year, $month, $approverName, $departments, $link)
    {
        $this->name = $name;
        $this->year = $year;
        $this->month = $month;
        $this->approverName = $approverName;
        $this->departments = $departments->name;
        $this->link = $link;
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
                    ->greeting("Hello Mr./Ms. ".' '. $this->name)
                    ->line(
                        'We would like to inform you that the MDR for ' . $this->departments .
                        ' in the month of ' . date('F Y', strtotime($this->year . '-' . $this->month . '-01')) .
                        ' was returned by ' . $this->approverName . '.'
                    )
                    ->action('Click the button to see your MDR', $this->link)
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
