<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SellerScope;

class Seller extends User
{
    use HasFactory;

    // No necesita atributos de manera específica ya que ya están extendiendo los de User

    protected static function boot() // Suele utilizarse para construir e inicializar el modelo
    {
        parent::boot(); 
        static::addGlobalScope(new SellerScope);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
