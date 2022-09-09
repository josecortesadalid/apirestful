<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get(); // recuerda que el modelo buyer tiene una relación transactions. Se lo ponemos al has y lo obtenemos con el get
        return response()->json(['data' => $compradores], 200);
    }

    public function show($id)
    {
        $comprador = Buyer::has('transactions')->findOrFail($id); 
        return response()->json(['data' => $comprador], 200);
    }

}
