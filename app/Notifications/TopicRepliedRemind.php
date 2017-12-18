<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicRepliedRemind extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
        //return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;

        return [
            'reply_id'      => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id'       => $this->reply->user->id,
            'user_name'     => $this->reply->user->name,
            'user_avatar'   => $this->reply->user->avatar,
            'topic_link'    => $topic->link(['#reply' . $this->reply->id]),
            'topic_id'      => $topic->id,
            'topic_title'   => $topic->title,
        ];
    }

    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
            ->line('在话题回复中有人@你！')
            ->action('查看回复', $url);
    }
}
