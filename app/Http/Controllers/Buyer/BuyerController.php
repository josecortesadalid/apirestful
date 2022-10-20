<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct(); 
        $this->middleware('scope:read-general')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get(); // recuerda que el modelo buyer tiene una relación transactions. Se lo ponemos al has y lo obtenemos con el get
        // return response()->json(['data' => $compradores], 200);
        return $this->showAll($compradores);
    }

    public function show(Buyer $buyer)
    {
        // $comprador = Buyer::has('transactions')->findOrFail($id); 

        return $this->showOne($buyer);
    }

}
