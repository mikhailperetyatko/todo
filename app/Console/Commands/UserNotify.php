<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Post;
use Carbon\Carbon;
use App\Notifications\UserNotification;

class UserNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify {dateStart} {dateEnd}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Послать уведомление по email об опубликованных статьях';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    
    public function getDate(string $date)
    {
        return Carbon::parse($date);
    }
    
    public function getPosts()
    {
        return Post::select('slug', 'title', 'created_at')
            ->whereBetween('created_at', [
                $this->getDate($this->argument('dateStart')),
                $this->getDate($this->argument('dateEnd')),
                ])
            ->publishedAndLatest()
            ->get()
        ;
    }
     
    public function handle()
    {
        $users = User::select('email')->get();
        $posts = $this->getPosts();
        \Notification::send($users, new UserNotification($posts));
    }
}
