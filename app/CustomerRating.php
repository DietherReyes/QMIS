<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'five_star', 'four_star', 'three_below', 'csm_id'
    ];

    public function customer_rating()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
