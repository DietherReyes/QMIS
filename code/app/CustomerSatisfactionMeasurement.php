<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionMeasurement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'functional_unit', 
        'year', 
        'quarter', 
        'total_customer', 
        'total_male', 
        'total_female', 
        'comments'
    ];
}
