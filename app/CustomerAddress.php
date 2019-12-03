<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'count','csm_id'
    ];

    public function other_files()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
