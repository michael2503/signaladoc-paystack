<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id',
        'reference',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'paid_at',
        'channel',
        'raw_payload',
    ];

    public function app()
    {
        return $this->belongsTo(App::class);
    }

}
