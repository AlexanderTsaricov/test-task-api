<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'website',
        'industry',
        'tax_id',
    ];

    /**
     * У компании может быть много аккаунтов.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
