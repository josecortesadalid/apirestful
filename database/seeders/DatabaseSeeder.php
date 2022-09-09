<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate(); // borra todo lo que hay en el interior de la tabla pero no la tabla
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuarios = 200;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        // factory(User::class, $cantidadUsuarios)->create();
        // factory(Category::class, $cantidadCategorias)->create();
        // factory(Transaction::class, $cantidadTransacciones)->create();

        User::factory()->count($cantidadUsuarios)->create();
        Category::factory()->count($cantidadCategorias)->create();
        Product::factory()->count($cantidadProductos)->create();
        Transaction::factory()->count($cantidadTransacciones)->create();

    }
}
