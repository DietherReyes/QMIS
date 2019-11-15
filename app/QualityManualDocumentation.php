<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QualityManualDocumentation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_code', 'revision_no', 'date', 'division', 'subject', 'quality_manual_doc'
    ];
}
