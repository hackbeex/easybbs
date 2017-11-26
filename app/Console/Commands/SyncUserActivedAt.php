<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserActivedAt extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'easybbs:sync-user-actived-at';

    /**
     * The console command description.
     */
    protected $description = 'Sync user last login time to db';

    /**
     * Execute the console command.
     */
    public function handle(User $user)
    {
        $user->syncUserActivedAt();
        $this->info("同步成功！");
    }
}
