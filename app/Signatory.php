<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'position', 'signature_photo'
    ];
}
