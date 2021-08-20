<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'composition'
    ];

    public function stock() {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function category() {
        return $this->hasMany(Category::class, 'product_id');
    }

    public function image() {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function history() {
        return $this->hasMany(History::class, 'product_id');
    }
}
