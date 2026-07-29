<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type',
    'title',
    'description',
    'status',
    'subject_type',
    'subject_id',
    'created_by',
])]
class activity_log extends Model
{
    //
}