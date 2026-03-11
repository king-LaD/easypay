<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded [
        'id'
        'ref_externe',
        'campay_id',
        'monnaie',
        'status'
    ];
}
