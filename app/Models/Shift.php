<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['start', 'end', 'organisation', 'paid_shift', 'invoice_amount', 'invoice_sent', 'invoice_paid', 'notes'];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'paid_shift' => 'boolean',
        'invoice_sent' => 'boolean',
        'invoice_paid' => 'boolean',
        'invoice_amount' => MoneyCast::class,
    ];
}
