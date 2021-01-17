<?php

namespace App;

use App\User;
use App\Comments;
use App\Activities;
use App\Useractivities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Useractivityreports extends Model
{
    public $table = "useractivity_reports";

    public $fillable = ['assign_id', 'resident', 'user', 'comment', 'sign_date'];
}
