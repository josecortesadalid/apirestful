<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(1),
            'quantity' => $this->faker->numberBetween(1, 10), // generamos números entre el 1 y el 10
            'status' => $this->faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]), // elemento aleatorio entre los dos posibles estados que tiene un producto
            'image' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
            // acceder a la lista de todos los usuarios y obtener uno de manera aleatoria
            // 'seller_id' => User::inRandomOrder()->first()->id,  la siguiente opción es más comprensible:
            'seller_id' => User::all()->random()->id,
        ];
    }
}
