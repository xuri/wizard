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
		// (1) Example query by limit and pagitation
		//
		// (2) Example query by time
		//
		// Retrive all register users
		$users = User::get();

		foreach ($users as $user) {

			// Initial Easemob SDK
			$easemob		= getEasemob();

			// Query and save chat log

			// Initial cursor
			$cursor = '';

			do{
				$chat_record = cURL::newJsonRequest('GET', 'https://a1.easemob.com/jinglingkj/pinai/chatmessages?ql=select+*+where+from+=+\'' . $user->id . '\'+and+timestamp+%3E+' . strtotime(Carbon::yesterday()) . '000&limit=200&cursor=' . $cursor)
							->setHeader('content-type', 'application/json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->send();

				// Get result
				$result =  json_decode($chat_record->body, true);

				// Determine cursor exist for next query
				if(isset($result['cursor']))
				{
					// Cursor exists
					$cursor = $result['cursor'];

					// Build format for storage
					$content =  substr(json_encode($result['entities']), 1, -1) . ',';

					// Determining log file exists
					if (File::exists(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'))) {
						// Appending to a File
					    File::append(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'), $content);
					} else {
						// Writing the Contents of a File (create or replace a file's contents)
					    File::append(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'), $content);
					}
				}
				else {
					// Cursor not exists and set it to null
					$cursor =  '';

					// Build format for storage
					// Build format for storage
					$content =  substr(json_encode($result['entities']), 1, -1) . ',';

					// Determining log file exists
					if (File::exists(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'))) {
						// Appending to a File
					    File::append(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'), $content);
					} else {
						// Writing the Contents of a File (create or replace a file's contents)
					    File::append(app_path('chatrecord/user_' . $user->id . '/' . date('Ymd') . '.log'), $content);
					}
				}

			} while ($cursor !== '');
		}
	}

}