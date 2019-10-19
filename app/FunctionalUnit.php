<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionalUnit extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'abbreviation', 'name', 'permission'
    ];
}
