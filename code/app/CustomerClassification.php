<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerClassification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student', 
        'government_employee', 
        'internal', 
        'business', 
        'homemaker', 
        'entrepreneur', 
        'private_organization', 
        'csm_id'
    ];

    public function customer_classification()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
