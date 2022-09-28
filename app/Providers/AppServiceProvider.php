<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); // Todas las cadenas de caracteres tendrán un tamaño por defecto de 191 y no de 255

        Product::updated(function($product)
        {
            if ($product->quantity == 0 && $product->estaDisponible()) {

                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                
                $product->save();
            }
        });
    }
}
