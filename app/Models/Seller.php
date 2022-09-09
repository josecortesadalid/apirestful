<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;

    // No necesita atributos de manera específica ya que ya están extendiendo los de User

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
