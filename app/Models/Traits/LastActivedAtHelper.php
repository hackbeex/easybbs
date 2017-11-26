<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hashPrefix = 'easybbs_last_actived_at_';
    protected $fieldPrefix = 'user_';

    public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名
        return $this->hashPrefix . $date;
    }

    public function getHashField()
    {
        // 字段名称
        return $this->fieldPrefix . $this->id;
    }

    public function recordLastActivedAt()
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->getHashFromDateString($date);

        $field = $this->getHashField();

        $now = Carbon::now()->toDateTimeString();

        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // 获取昨天的日期的键
        $hash = $this->getHashFromDateString(Carbon::now()->subDay()->toDateString());

        $dates = Redis::hGetAll($hash);

        foreach ($dates as $userId => $activedAt) {
            // 会将 `user_1` 转换为 1
            $userId = str_replace($this->fieldPrefix, '', $userId);

            if ($user = $this->find($userId)) {
                $user->last_actived_at = $activedAt;
                $user->save();
            }
        }

        // 以数据库为中心的存储，既已同步，即可删除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        $field = $this->getHashField();

        // 优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash, $field) ? : $value;

        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }
}