<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->middleware('scope:read-general')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')
        ->get();
        // ->pluck('product'); 
        // si ponemos transactions sin el paréntesis, lo que obtenemos es una colección. Al ser una colección, deja de ser una transacción, por lo que no podemos acceder al producto de la misma
        // con los paréntesis llamamos a la función en vez de a la relación
        // con el with, obtendremos una lista de transacciones que tendrán en su interior el producto

        // dd($products);
        return $this->showAll($products);
    }
}
