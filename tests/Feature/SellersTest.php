<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    use WithFaker; // trait para utilizar faker

    public function test_index()
    {
        $this->json('get','/sellers')->assertStatus(200)->assertJsonStructure([ // obtengo la lista de usuarios
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'verified',
                    'admin',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]
        ]); 
    }
    public function test_show()
    {

        //PASOS
        // Crear el usuario con un post a users
        // Crear el producto con ese usuario (con el id). Ejemplo de un post a: /sellers/202/products

        $seller = Seller::create([ // Creo un usuario
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password'      => '12341234',
            'password_confirmation'      => '12341234'
        ]);
        
        $this->json('get',"/sellers/$seller->id")->assertStatus(200)->assertJsonStructure([ // Lo busco con el show
            'data' => [
                 
                    'name',
                    'email',
                    'verified',
                    'admin',
                    'updated_at',
                    'created_at',
                    'id',
                    'deleted_at'
            ]
        ]); 
    }

}
