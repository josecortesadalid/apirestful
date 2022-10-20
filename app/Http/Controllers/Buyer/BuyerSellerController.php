<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct(); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $this->allowedAdminAction();
        $sellers = $buyer->transactions()->with('product.seller') // al poner .seller, laravel se encarga de resolver las transacciones junto con la lista de productos y el vendedor de cada uno de esos productos
        ->get()
        ->pluck('product.seller')
        ->unique('id')  // Deben tener id diferente, es para que no se repitan estos vendedores
        ->values(); // reorganiza los indices ya que con el unique, si tengo varios vendedores, quedarían elementos vacíos en el lugar de los vendedores (repetidos) que se borran
        return $this->showAll($sellers);
    }
}
