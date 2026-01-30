<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'paystack_public_key',
        'paystack_secret_key',
        'callback_url',
        'webhook_secret',
        'environment',
    ];
}
