<?php

namespace App\Observers;

use App\Models\Topic;
use App\Tools\SlugTranslateTool;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);

        if ( ! $topic->slug) {
            $topic->slug = app(SlugTranslateTool::class)->translate($topic->title);
        }
    }
}