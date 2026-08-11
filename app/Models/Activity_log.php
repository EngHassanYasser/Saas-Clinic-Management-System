<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'type',
    'title',
    'description',
    'status',
    'subject_type',
    'subject_id',
    'created_by',
])]
class Activity_log extends Model
{
    use HasFactory;
}