<?php

// use Indatus\Dispatcher\Scheduling\ScheduledCommand;
// use Indatus\Dispatcher\Scheduling\Schedulable;
// use Indatus\Dispatcher\Drivers\Cron\Scheduler;
// use Symfony\Component\Console\Input\InputOption;
// use Symfony\Component\Console\Input\InputArgument;

// class forumopen extends ScheduledCommand {

//     /**
//      * The console command name.
//      *
//      * @var string
//      */
//     protected $name = 'command:forumopen';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Timing open forum category';

//     /**
//      * Create a new command instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         parent::__construct();
//     }

//     /**
//      * When a command should run
//      *
//      * @param Scheduler $scheduler
//      * @return \Indatus\Dispatcher\Scheduling\Schedulable
//      */
//     public function schedule(Schedulable $scheduler)
//     {
//         return $scheduler->daily()->hours(21)->minutes(0);
//     }

//     /**
//      * Execute the console command.
//      *
//      * @return mixed
//      */
//     public function fire()
//     {
//         // Retrieve forum category
//         $category       = ForumCategories::find(1);

//         // Open forum
//         $category->open = 1;
//         $category->save();
//     }

//     // /**
//     //  * Get the console command arguments.
//     //  *
//     //  * @return array
//     //  */
//     // protected function getArguments()
//     // {
//     //  return array(
//     //      array('example', InputArgument::REQUIRED, 'An example argument.'),
//     //  );
//     // }

//     // /**
//     //  * Get the console command options.
//     //  *
//     //  * @return array
//     //  */
//     // protected function getOptions()
//     // {
//     //  return array(
//     //      array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
//     //  );
//     // }

// }
