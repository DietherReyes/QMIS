<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReview extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_name', 
        'venue', 
        'date', 
        'minutes', 
        'action_plan', 
        'agenda_memo', 
        'attendance_sheet', 
        'description'
    ];
}
