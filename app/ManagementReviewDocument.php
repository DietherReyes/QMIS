<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name', 
        'type',
        'manrev_id'
    ];

    public function other_files()
    {
        return $this->belongsTo('App\ManagementReview');
    }
}
