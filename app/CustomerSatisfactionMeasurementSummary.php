<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionMeasurementSummary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'functional_unit', 
        'year',
        'total_customer',
        'q1_overall_rating',
        'q2_overall_rating',
        'q3_overall_rating',
        'q4_overall_rating',
        'response_delivery',
        'work_quality',
        'personnels_quality',
        'overall_rating',
        'adjectival_rating',
    ];
}
