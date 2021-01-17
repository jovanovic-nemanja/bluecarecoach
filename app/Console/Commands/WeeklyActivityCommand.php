<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use App\Activities;
use App\Notifications;
use App\Useractivities;
use App\ReminderConfigs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WeeklyActivityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activityweekly:checkdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cur_date = User::getformattime();
        $cur_day = Carbon::parse($cur_date['dates']);

        /*
            SELECT * FROM user_activities
                INNER JOIN activities on activities.id = user_activities.activities
                INNER JOIN users on users.id = user_activities.resident
                WHERE user_activities.type = 2
                AND user_activities.start_day <= '2020-12-18'
                AND user_activities.end_day >= '2020-12-30'
        */

        $user_activities = DB::table('user_activities')
                            ->select('user_activities.*', 'activities.*', 'users.*', 'users.firstname as u_name', 'activities.type as act_type')
                            ->Join('activities', 'activities.id', '=', 'user_activities.activities')
                            ->Join('users', 'users.id', '=', 'user_activities.resident')
                            ->where('user_activities.type', 2)
                            ->whereDate('user_activities.start_day', '<=', $cur_day)
                            ->whereDate('user_activities.end_day', '>=', $cur_day)
                            ->get();

        if (@$user_activities) {
            foreach ($user_activities as $user_activity) {
                $dt = Carbon::parse($cur_date['date']); 
                $week_Day = $dt->dayOfWeek; //dayOfWeek returns a number between 0 (sunday) and 6 (saturday)
                if ($week_Day == 0) {   //sunday
                    $week_d = 7;
                }else{
                    $week_d = $week_Day;
                }

                if ($user_activity->day == $week_Day) { //validate week day
                    $activity_time = $user_activity->time;

                    if (@$activity_time) {
                        $startTime = Carbon::parse(User::formattime1($activity_time));
                        $finishTime = Carbon::parse(User::formattime1($cur_date['time']));
                        if ($startTime > $finishTime) {
                            $sym = "";
                        }else{
                            $sym = "-";
                        }
                        $totalDuration = $sym.$finishTime->diffInSeconds($startTime);
                    }else {
                        $totalDuration = "";
                    }

                    $reminders = ReminderConfigs::where('active', 1)->first();
                    $reminder_minutes = $reminders->minutes * 60;
                    $activity_type_name = Activities::getTypeasstring($user_activity->act_type);

                    if ($totalDuration == $reminder_minutes) {
                        $record = Notifications::create([
                            'user_name' => 'admin',
                            'resident_name' => $user_activity->u_name,
                            'contents' => "Weekly " . $activity_type_name  . " : " . $user_activity->title,
                            'is_read' => 1,
                            'sign_date' => $cur_date['date'],
                        ]);
                    }
                } 
            }
        }
    }
}
