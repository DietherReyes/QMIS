<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOverallRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_delivery', 
        'work_quality', 
        'personnels_quality', 
        'overall_rating', 
        'csm_id'
    ];

    public function customer_overall_rating()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
