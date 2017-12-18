<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;
use App\Notifications\TopicRepliedRemind;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $topic->increment('reply_count', 1);

        // 如果评论的作者是话题的作者，不需要通知
        if ( ! $reply->user->isAuthorOf($topic)) {
            $topic->user->notify(new TopicReplied($reply));
        }

        // @某人通知
        if (preg_match_all('#@(\S+?) #', $reply->content, $matches)) {
            $userNames = array_unique($matches[1]);
            $users = User::whereIn('name', $userNames)->get();
            foreach ($users as $user) {
                $user->notify(new TopicRepliedRemind($reply));
            }
        }
    }

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count', 1);
    }
}