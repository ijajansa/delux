<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothType extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }
}
