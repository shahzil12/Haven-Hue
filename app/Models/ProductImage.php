<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_path', 'is_primary'];

    protected $appends = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        if (empty($this->image_path)) {
            return 'https://placehold.co/600x600?text=No+Image';
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://') || str_starts_with($this->image_path, 'data:')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }
}
