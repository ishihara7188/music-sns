<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;


class EmailVerificationJa extends Notification
{
    public static $toMailCallback;

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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
                    ->subject('ユーザー認証メール')
                    ->line('下記のボタンをクリックして、ユーザー認証を完了してください。')
                    ->action(
                        '認証',
                        $this->verificationUrl($notifiable)
                    )
                    ->line('本メールに心当たりのない場合、メールを破棄するか、お問い合わせフォームよりご確認ください。');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}