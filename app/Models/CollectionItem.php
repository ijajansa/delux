<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $fillable = ['collection_id', 'cloth_type_id', 'quantity'];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function clothType()
    {
        return $this->belongsTo(ClothType::class);
    }
}
