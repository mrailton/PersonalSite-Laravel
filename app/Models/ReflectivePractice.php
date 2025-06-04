<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReflectivePractice extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'date', 'description', 'feelings', 'evaluation', 'analysis', 'conclusion', 'action_plan'];

    protected $casts = [
        'date' => 'date',
    ];
}
