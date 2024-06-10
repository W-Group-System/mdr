<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApproverNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $approverName;
    protected $yearAndMonth;
    protected $hrName;
    protected $department;
    protected $typeOfPenalties;
    public function __construct($approverName, $yearAndMonth, $hrName, $department, $typeOfPenalties)
    {
        $this->approverName = $approverName;
        $this->yearAndMonth = $yearAndMonth;
        $this->hrName = $hrName;
        $this->department = $department;
        $this->typeOfPenalties = $typeOfPenalties;
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
                    ->greeting('Hello Mr/Ms. ' . $this->approverName)
                    ->line('This email is notify you that Mr./Ms. ' . $this->hrName . ' Upload a '.$this->typeOfPenalties.' in ' . $this->department . ' in the month of ' . date('F Y', strtotime($this->yearAndMonth)))
                    // ->action('Notification Action', url('/'))
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
