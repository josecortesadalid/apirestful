<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
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
    public function index(Category $category)
    {
        $transactions = $category->products()
        ->whereHas('transactions')
        ->with('transactions') // Existe la posibilidad de que alguna/s de estas transacciones estén vacías porque ese producto aún no tiene ninguna transacción asociada
        ->get();
        // ->pluck('transactions')
        // ->collapse();
        return $this->showAll($transactions);
    }

}
