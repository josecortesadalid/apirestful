<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
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

        User::created(function($user) { // cuando se crea un usuario 
            Mail::to($user)->send(new UserCreated($user)); // ponemos solo $user ya que laravel se encarga de coger automáticamente el valor del campo email
            // añadimos la instancia del mailable
        });

        User::updated(function($user) {
            if ($user->isDirty('email')) { // isDirty nos permitirá saber si el email ha cambiado. Si no le pusiesemos parámetros, comprobaría todos los atributos del modelo y si alguno de estos cambió
                Mail::to($user)->send(new UserMailChanged($user));
            }
        });

        Product::updated(function($product)
        {
            if ($product->quantity == 0 && $product->estaDisponible()) {

                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                
                $product->save();
            }
        });
    }
}
