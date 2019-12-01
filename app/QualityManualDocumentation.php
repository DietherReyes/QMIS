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
        'document_code', 'revision_number', 'page_number', 'effectivity_date', 'section', 'subject', 'quality_manual_doc'
    ];
}
