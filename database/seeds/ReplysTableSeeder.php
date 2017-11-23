<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $user_ids = User::all()->pluck('id')->toArray();
        $topic_ids = Topic::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        $replies = factory(Reply::class)->times(1000)->make()->each(function ($reply, $index) use ($user_ids, $topic_ids, $faker) {
            $reply->user_id = $faker->randomElement($user_ids);
            $reply->topic_id = $faker->randomElement($topic_ids);
        });

        // 将数据集合转换为数组，并插入到数据库中
        Reply::insert($replies->toArray());
    }

}

