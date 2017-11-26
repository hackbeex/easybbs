<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'easybbs:calculate-active-user';

    /**
     * The console command description.
     */
    protected $description = 'Generate active users';

    /**
     * Execute the console command.
     */
    public function handle(User $user)
    {
        $this->info("开始计算...");

        $user->calculateAndCacheActiveUsers();

        $this->info("成功生成！");
    }
}
