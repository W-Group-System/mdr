<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotificationForApprovers extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $user;
    private $dept;
    private $year;
    private $month;
    
    public function __construct($user, $dept, $year, $month)
    {
        $this->user = $user->name;
        $this->dept = $dept->name;
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
                    ->greeting('Hello Mr./Ms. ' . $this->user)
                    ->line('This is the request approval for the '. $this->dept. ' in the Month of ' . date('F Y', strtotime($this->year . '-' . $this->month . '-01')))
                    ->action('Pending Approval', url('for_approval'))
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
