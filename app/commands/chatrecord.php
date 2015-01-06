<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class chatrecord extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:chatrecord';

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
		// Retrive all register users
		$users = User::get();

		foreach ($users as $user) {
			$easemob		= getEasemob();
			// Query and save Chat Log
			//
			// (1) Example query by limit and pagitation
			//
			// $test = cURL::newJsonRequest('GET', 'https://a1.easemob.com/jinglingkj/pinai/chatmessages'.'?limit=9999999999&cursor=LTU2ODc0MzQzOmtkS1JHblUxRWVTbDdDc1NhVGcxUGc')
			//
			// (2) Example query by time
			//
			$chat_record = cURL::newJsonRequest('GET', 'https://a1.easemob.com/jinglingkj/pinai/chatmessages'.'?limit=9999999999&?ql=select+from+' . $user->id . '+where+timestamp%3C' . time() . '+and+timestamp%3E' . strtotime(Carbon::yesterday()) )
				->setHeader('content-type', 'application/json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->send();

			// Determining If a File Exists
			if (File::exists(app_path('chatrecord/' . $user->id . '.log'))) {
				// Appending to a File
			    File::append(app_path('chatrecord/' . $user->id . '.log'), $chat_record->body);
			} else {
				// Writing the Contents of a File (create or replace a file's contents)
			    File::append(app_path('chatrecord/' . $user->id . '.log'), $chat_record->body);
			}

		}
	}


}
