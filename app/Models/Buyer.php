<?php

namespace App\Models;


use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Buyer extends User
{
    use HasFactory;

    public $transformer = BuyerTransformer::class;

    // No necesita atributos de manera específica ya que ya están extendiendo los de User

    protected static function boot() // Suele utilizarse para construir e inicializar el modelo
    {
        parent::boot(); // importante para conservar el funcionamiento original de Laravel
        static::addGlobalScope(new BuyerScope);
        // Utilizamos el operador static debido a que estamos en un método estático y para referirnos al método propio de la clase es preferible hacer uso de este operador
        // Añadimos el Scope que hemos creados para que Laravel lo utilice cada vez que se haga una consulta
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
