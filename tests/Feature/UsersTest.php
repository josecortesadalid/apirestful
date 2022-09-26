<?php

namespace Tests\Feature;

use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use WithFaker; // trait para utilizar faker
    public function test_lista()
    {
        $this->json('get','/users')->assertStatus(200)->assertJsonStructure([ // obtengo la lista de usuarios
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

    public function test_store()
    {

        $user = [ 
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password' => '12341234',
            'password_confirmation' => '12341234'
        ];

        $this->json('post','/users', $user)->assertStatus(200)->assertJsonStructure([ // hago un post
            'data' => [
                 
                    'name',
                    'email',
                    'verified',
                    'admin',
                    'updated_at',
                    'created_at',
                    'id'
            ]
        ]); 

        // $this->assertDatabaseHas('users', $user);
    }

    public function test_show()
    {
        $user = User::create([ // Creo un usuario
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password'      => '12341234',
            'password_confirmation'      => '12341234'
        ]);
        
        $this->json('get',"/users/$user->id")->assertStatus(200)->assertJsonStructure([ // Lo busco con el show
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

    public function test_destroy()
    {

        $userData = [ 
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password'      => '12341234',
            'password_confirmation'      => '12341234'
        ];

        $user = User::create(
            $userData
        );

        $this->json('delete',"/users/$user->id")->assertStatus(200)->assertJsonStructure([ // hago un post
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

    public function test_update()
    {
        $user = User::create([ // Creo un usuario
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password'      => '12341234',
            'password_confirmation'      => '12341234'
        ]);

        $actualizado = [ 
            'name' => $this->faker->name,
            'email'  => $this->faker->email,
            'password'      => '12341234',
            'password_confirmation'      => '12341234'
        ];
        
        $this->json('put',"/users/$user->id", $actualizado)->assertStatus(200)->assertJsonStructure([ // Lo busco con el show
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
