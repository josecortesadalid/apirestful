<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct(); // llamamos al constructor de la clase padre
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,buyer')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get();
        // ->pluck('product.categories')
        // ->collapse(); // para coger la series de listas que hemos obtenido y juntarlas en una sola lista
        // ->unique('id')
        // ->values();
        // dd($categories);




        $result = array();
        foreach ($categories as $category ) {
            array_push($result, $category->product->categories);
        }
        $collection = collect($result);

        // dd($collection);

        return $this->showAll($collection);
    }
}
