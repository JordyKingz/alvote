<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Invitation;

class RoomInvitation extends Notification
{
    use Queueable;

     /**
     * User Object
     *
     * @var App\Models\Invitation
     */
    public $invite;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invite)
    {
        $this->invite = $invite;
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
      $vueApp = config('app.url');
      $url = url($vueApp . '/join/room?room_code='.$this->invite->room_code . '&personal_code='.$this->invite->personal_code);

      return (new MailMessage)
          ->from('no-reply@alvote.nl')
          ->subject('ALVote: Invitation to new conference room')
          ->markdown('email.room.invitation', [
              'url' => $url,
              'room_code' => $this->invite->room_code,
              'personal_code' => $this->invite->personal_code,
          ]);
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
