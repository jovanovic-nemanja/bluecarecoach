<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use App\Medications;
use App\Notifications;
use App\ReminderConfigs;
use App\Assignmedications;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MedicationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medications:checkdata';

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

        $assign_medications = DB::table('assign_medications')
                            ->select('assign_medications.*', 'medications.*', 'assign_medications.sign_date as assign_date', 'users.*', 'medications.name as med_name', 'users.firstname as u_name')
                            ->Join('medications', 'medications.id', '=', 'assign_medications.medications')
                            ->Join('users', 'users.id', '=', 'assign_medications.resident')
                            ->whereDate('assign_medications.start_day', '<=', $cur_day)
                            ->whereDate('assign_medications.end_day', '>=', $cur_day)
                            ->get();

        if (@$assign_medications) {
            foreach ($assign_medications as $assign_medication) {
                $ass_date = Carbon::parse($assign_medication->assign_date);
                $cur_date['dates'] = Carbon::parse($cur_date['date']); 

                // if ($ass_date->addDays($assign_medication->duration) >= $cur_date['dates']) {   
                    $assign_time = $assign_medication->time;
                    if ($assign_time) {
                        $startTime = Carbon::parse(User::formattime1($assign_time));
                        $finishTime = Carbon::parse(User::formattime1($cur_date['time']));
                        if ($startTime > $finishTime) {
                            $sym = "";
                        }else{
                            $sym = "-";
                        }
                        $totalDuration1 = $sym.$finishTime->diffInSeconds($startTime);
                    }else {
                        $totalDuration1 = "";
                    }

                    $reminders = ReminderConfigs::where('active', 1)->first();
                    $reminder_minutes = $reminders->minutes * 60;

                    if ($totalDuration1 == $reminder_minutes) {
                        $record = Notifications::create([
                            'user_name' => 'admin',
                            'resident_name' => $assign_medication->u_name,
                            'contents' => "Medication : " . $assign_medication->med_name . " " . $assign_medication->time,
                            'is_read' => 1,
                            'sign_date' => $cur_date['date'],
                        ]);
                    }
                // }
            }
        }
    }
}
