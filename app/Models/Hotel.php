<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = ['name', 'is_active', 'partner_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
