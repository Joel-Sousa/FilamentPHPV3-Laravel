<?php

namespace App\Models;

use App\Traits\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory, BelongsToTenantTrait;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'about',
        'phone',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
