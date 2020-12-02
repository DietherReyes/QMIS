<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOtherClassification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'count', 
        'csm_id'
    ];

    public function customer_other_classification()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
