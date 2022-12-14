<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $this->allowedAdminAction();
        $buyers = $product->transactions()
        ->with('buyer')
        ->get()
        ->pluck('buyer')
        ->unique('id')
        ->values();
        return $this->showAll($buyers);
    }
}
