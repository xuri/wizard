<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class points extends ScheduledCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily record user chat log from easemob, get support to visit: http://xuri.me';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * When a command should run
     *
     * @param Scheduler $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler->daily()->hours(3)->minutes(10);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        echo 'start process...';

        $users = User::where('id', '>', '1')->orderBy('points', 'desc')->get()->toArray();

        foreach ($users as $key) {
            $user = User::find($key['id']);
            $profile = Profile::where('user_id', $user->id)->first();
            if ($profile) {
                $point = User::find($key['id'])->first();
                $point->points = $profile->renew + 10;
                $point->save();
            }

        }

        echo 'SUCCESS!!';

    }

}
