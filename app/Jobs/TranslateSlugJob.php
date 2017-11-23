<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Topic;
use App\Tools\SlugTranslateTool;

class TranslateSlugJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic; //只序列化模型的 ID
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $slug = app(SlugTranslateTool::class)->translate($this->topic->title);

        // 为了避免模型监控器死循环调用，我们使用 DB 类直接对数据库进行操作
        \DB::table(Topic::getModel()->getTable())->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
