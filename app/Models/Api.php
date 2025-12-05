<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'get',
        'put',
        'delete',
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
