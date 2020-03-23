<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionDocuments extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name', 
        'csm_id'
    ];

    public function other_files()
    {
        return $this->belongsTo('App\CustomerSatisfactionMeasurement');
    }
}
