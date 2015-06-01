<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class analytics extends ScheduledCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically analytics.';

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
        // Every day at 2:10 AM
        return $scheduler->daily()->hours(2)->minutes(10);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        /*
        |--------------------------------------------------------------------------
        | User Analytics Section
        |--------------------------------------------------------------------------
        |
        */

        // All register users
        $allUser                                    = User::get()->count();

        // Daily active user
        $dailyActiveUser                            = User::where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Weekly active user
        $weeklyActiveUser                           = User::where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

        // Monthly active user
        $monthlyActiveUser                          = User::where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

        // Male

        // All register male users
        $allMaleUser                                = User::where('sex', 'M')->get()->count();

        // Daily active male user
        $dailyActiveMaleUser                        = User::where('sex', 'M')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Weekly active male user
        $weeklyActiveMaleUser                       = User::where('sex', 'M')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

        // Monthly active male user
        $monthlyActiveMaleUser                      = User::where('sex', 'M')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

        // Female

        // All register female users
        $allFemailUser                              = User::where('sex', 'F')->get()->count();

        // Daily active female user
        $dailyActiveFemaleUser                      = User::where('sex', 'F')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Weekly active female user
        $weeklyActiveFemaleUser                     = User::where('sex', 'F')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

        // Monthly active female user
        $monthlyActiveFemaleUser                    = User::where('sex', 'F')->where('updated_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

        // Complete profile user
        $completeProfileUserRatio                   = number_format((User::whereNotNull('portrait')->count() / $allUser) * 100, 2);

        // User register with client

        // Register from website
        $fromWeb                                    = User::where('from', 0)->count();

        // Register from Android App
        $fromAndroid                                = User::where('from', 1)->count();

        // Register from iOS App
        $fromiOS                                    = User::where('from', 2)->count();

        // Store analytics data
        $analyticsUser                              = new AnalyticsUser;
        $analyticsUser->all_user                    = $allUser;
        $analyticsUser->daily_active_user           = $dailyActiveUser;
        $analyticsUser->weekly_active_user          = $weeklyActiveUser;
        $analyticsUser->monthly_active_user         = $monthlyActiveUser;
        $analyticsUser->all_male_user               = $allMaleUser;
        $analyticsUser->daily_active_male_user      = $dailyActiveMaleUser;
        $analyticsUser->weekly_active_male_user     = $weeklyActiveMaleUser;
        $analyticsUser->monthly_active_male_user    = $monthlyActiveMaleUser;
        $analyticsUser->all_female_user             = $allFemailUser;
        $analyticsUser->daily_active_female_user    = $dailyActiveFemaleUser;
        $analyticsUser->weekly_active_female_user   = $weeklyActiveFemaleUser;
        $analyticsUser->monthly_active_female_user  = $monthlyActiveFemaleUser;
        $analyticsUser->complete_profile_user_ratio = $completeProfileUserRatio;
        $analyticsUser->from_web                    = $fromWeb;
        $analyticsUser->from_android                = $fromAndroid;
        $analyticsUser->from_ios                    = $fromiOS;
        $analyticsUser->save();

        /*
        |--------------------------------------------------------------------------
        | Forum Analytics Stction
        |--------------------------------------------------------------------------
        |
        */

        // Total posts

        // All posts
        $allPost            = ForumPost::get()->count();

        // Category 1 posts
        $cat1Post           = ForumPost::where('category_id', 1)->count();

        // Category 2 posts
        $cat2Post           = ForumPost::where('category_id', 2)->count();

        // Category 3 posts
        $cat3Post           = ForumPost::where('category_id', 3)->count();

        // Daily posts analytics

        // Daily posts
        $dailyPost          = ForumPost::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Category 1 daily posts
        $cat1DailyPost      = ForumPost::where('category_id', 1)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Category 2 daily posts
        $cat2DailyPost      = ForumPost::where('category_id', 2)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Category 3 daily posts
        $cat3DailyPost      = ForumPost::where('category_id', 3)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Daily male posts
        $dailyMalePostArray = ForumPost::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->select('user_id')->get()->toArray();
        $dailyMalePost      = 0;
        foreach ($dailyMalePostArray as $key => $value) {
            $user = User::where('id', $dailyMalePostArray[$key]['user_id'])->first();
            if($user->sex == 'M')
            {
                $dailyMalePost = $dailyMalePost + 1;
            }
        }

        // Daile female posts
        $dailyFemalePost                    = $dailyPost - $dailyMalePost;

        // Store analytics data
        $analyticsForum                     = new AnalyticsForum;
        $analyticsForum->all_post           = $allPost;
        $analyticsForum->cat1_post          = $cat1Post;
        $analyticsForum->cat2_post          = $cat2Post;
        $analyticsForum->cat3_post          = $cat3Post;
        $analyticsForum->daily_post         = $dailyPost;
        $analyticsForum->cat1_daily_post    = $cat1DailyPost;
        $analyticsForum->cat2_daily_post    = $cat2DailyPost;
        $analyticsForum->cat3_daily_post    = $cat3DailyPost;
        $analyticsForum->daily_male_post    = $dailyMalePost;
        $analyticsForum->daily_female_post  = $dailyFemalePost;
        $analyticsForum->save();

        /*
        |--------------------------------------------------------------------------
        | User Like Analytics Section
        |--------------------------------------------------------------------------
        |
        */

        // Daily likes
        $dailyLike          = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

        // Weekly Like
        $weeklyLike         = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

        // Monthly Like
        $monthlyLike        = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

        // All male like female
        $allMaleLikeArray   = Like::select('sender_id')->get()->toArray();
        $allMaleLike        = 0;
        foreach ($allMaleLikeArray as $key => $value) {
            $user = User::where('id', $allMaleLikeArray[$key]['sender_id'])->first();
            if($user->sex == 'M')
            {
                $allMaleLike = $allMaleLike + 1;
            }
        }

        // All female like female
        $allFemaleLikeArray = Like::select('sender_id')->get()->toArray();
        $allFemaleLike      = 0;
        foreach ($allFemaleLikeArray as $key => $value) {
            $user = User::where('id', $allFemaleLikeArray[$key]['sender_id'])->first();
            if($user->sex == 'F')
            {
                $allFemaleLike = $allFemaleLike + 1;
            }
        }

        // Daily male like female
        $dailyMaleLikeArray = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->select('sender_id')->get()->toArray();
        $dailyMaleLike = 0;
        foreach ($dailyMaleLikeArray as $key => $value) {
            $user = User::where('id', $dailyMaleLikeArray[$key]['sender_id'])->first();
            if($user->sex == 'M')
            {
                $dailyMaleLike = $dailyMaleLike + 1;
            }
        }

        // Weekly male like female
        $weeklyMaleLikeArray    = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->select('sender_id')->get()->toArray();
        $weeklyMaleLike = 0;
        foreach ($weeklyMaleLikeArray as $key => $value) {
            $user = User::where('id', $weeklyMaleLikeArray[$key]['sender_id'])->first();
            if($user->sex == 'M')
            {
                $weeklyMaleLike = $weeklyMaleLike + 1;
            }
        }

        // Monthly male like female
        $monthlyMaleLikeArray   = Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->select('sender_id')->get()->toArray();
        $monthlyMaleLike = 0;
        foreach ($monthlyMaleLikeArray as $key => $value) {
            $user = User::where('id', $monthlyMaleLikeArray[$key]['sender_id'])->first();
            if($user->sex == 'M')
            {
                $monthlyMaleLike = $monthlyMaleLike + 1;
            }
        }

        // Daily female like male
        $dailyFemaleLike    = $dailyLike - $dailyMaleLike;

        // Weekly female like male
        $weeklyFemaleLike   = $weeklyLike - $weeklyMaleLike;

        // Monthly female like male
        $monthlyFemaleLike  = $monthlyLike - $monthlyMaleLike;

        // Male accept ratio (Female like male)
        $allAccept          = Like::where('status', 1)->count();
        $allMaleAcceptArray = Like::where('status', 1)->select('receiver_id')->get()->toArray();
        $allMaleAccept      = 0;
        foreach ($allMaleAcceptArray as $key => $value) {
            $user = User::where('id', $allMaleAcceptArray[$key]['receiver_id'])->first();
            if($user->sex == 'M')
            {
                $allMaleAccept = $allMaleAccept + 1;
            }
        }
        $allMaleAcceptRatio     = number_format(($allMaleAccept / $allAccept) * 100, 2);

        // Female accept ratio
        $allFemaleAccept        = $allAccept - $allMaleAccept;
        $allFemaleAcceptRatio   = number_format(($allFemaleAccept / $allAccept) * 100, 2);


        // Average like duration
        $likeDurationArray      = Like::where('status', 1)->select('created_at', 'updated_at')->get()->toArray(); // Retrieve all accept like as an array
        foreach($likeDurationArray as $key => $field){

            // Calculate duration days
            $likeDurationArray[$key]['duration']    = diffBetweenTwoDays(date("Y-m-d",strtotime($likeDurationArray[$key]['updated_at'])), date("Y-m-d",strtotime($likeDurationArray[$key]['created_at'])));

            // Summary like duration to a new array
            $sumLikeDurationArray[]                 = $likeDurationArray[$key]['duration'];
        }

        $averageLikeDuration                    = number_format(array_sum($sumLikeDurationArray) / count($sumLikeDurationArray), 2);

        // Store analytics data
        $analyticsLike                          = new AnalyticsLike;
        $analyticsLike->daily_like              = $dailyLike;
        $analyticsLike->weekly_like             = $weeklyLike;
        $analyticsLike->monthly_like            = $monthlyLike;
        $analyticsLike->all_male_like           = $allMaleLike;
        $analyticsLike->all_female_like         = $allFemaleLike;
        $analyticsLike->daily_male_like         = $dailyMaleLike;
        $analyticsLike->weekly_male_like        = $weeklyMaleLike;
        $analyticsLike->monthly_male_like       = $monthlyMaleLike;
        $analyticsLike->daily_female_like       = $dailyFemaleLike;
        $analyticsLike->weekly_female_like      = $weeklyFemaleLike;
        $analyticsLike->monthly_female_like     = $monthlyFemaleLike;
        $analyticsLike->all_male_accept_ratio   = $allMaleAcceptRatio;
        $analyticsLike->all_female_accept_ratio = $allFemaleAcceptRatio;
        $analyticsLike->average_like_duration   = $averageLikeDuration;
        $analyticsLike->save();
    }

}
