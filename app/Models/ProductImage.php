<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id' , 'url' , 'mime' , 'path' , 'created_by'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
