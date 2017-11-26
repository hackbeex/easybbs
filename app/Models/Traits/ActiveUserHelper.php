<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topicWeight = 4; // 话题权重
    protected $replyWeight = 1; // 回复权重
    protected $passDays = 7;    // 多少天内发表过内容
    protected $userNumber = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cacheKey = 'easybbs_active_users';
    protected $cacheExpireInMinutes = 65; //大点保证获取时不会过期

    public function getActiveUsers()
    {
        return Cache::remember($this->cacheKey, $this->cacheExpireInMinutes, function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $activeUsers = $this->calculateActiveUsers();
        $this->cacheActiveUsers($activeUsers);
    }

    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });

        // 我们需要的是倒序，高分靠前，第二个参数为保持数组的 KEY 不变
        $users = array_reverse($users, true);

        // 只获取我们想要的数量
        $users = array_slice($users, 0, $this->userNumber, true);

        // 新建一个空集合
        $activeUsers = collect();

        foreach ($users as $userId => $user) {
            if ($user = $this->find($userId)) {
                $activeUsers->push($user);
            }
        }

        // 返回数据
        return $activeUsers;
    }

    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$passDays）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topicUsers = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();
        // 根据话题数量计算得分
        foreach ($topicUsers as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topicWeight;
        }
    }

    private function calculateReplyScore()
    {
        // 从回复数据表里取出限定时间范围（$passDays）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $replyUsers = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();
        // 根据回复数量计算得分
        foreach ($replyUsers as $value) {
            $replyScore = $value->reply_count * $this->replyWeight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $replyScore;
            } else {
                $this->users[$value->user_id]['score'] = $replyScore;
            }
        }
    }

    private function cacheActiveUsers($activeUsers)
    {
        Cache::put($this->cacheKey, $activeUsers, $this->cacheExpireInMinutes);
    }
}