<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'api_id',
        'token_type_id',
        'token',
        'expires_at',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function api()
    {
        return $this->belongsTo(Api::class);
    }

    public function tokenType()
    {
        return $this->belongsTo(TokenType::class);
    }
}
