<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $guarded = [
        'id',
        'ref_externe',
        'campay_id',
        'monnaie',
        'status',
    ];
}
