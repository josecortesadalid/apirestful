<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = Seller::has('products')->get(); // recuerda que el modelo seller tiene una relación products. Se lo ponemos al has y lo obtenemos con el get
        // return response()->json(['data' => $vendedores], 200);
        return $this->showAll($vendedores);
    }

    public function show($id)
    {
        $vendedor = Seller::has('products')->findOrFail($id); 
        // return response()->json(['data' => $vendedor], 200);
        return $this->showOne($vendedor);
    }

}
