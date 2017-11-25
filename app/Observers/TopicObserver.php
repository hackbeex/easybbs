<?php

namespace App\Observers;

use App\Models\Topic;
use App\Models\Reply;
use App\Jobs\TranslateSlugJob;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body'); // XSS filter

        $topic->excerpt = make_excerpt($topic->body);
    }

    public function saved(Topic $topic)
    {
        if ( ! $topic->slug) {
            dispatch(new TranslateSlugJob($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        \DB::table(Reply::getModel()->getTable())->where('topic_id', $topic->id)->delete();
    }
}