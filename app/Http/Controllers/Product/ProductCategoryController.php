<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product, Category $category)
    {
        // sync lo sustituye pero no lo agrega
        // $product->categories()->sync([$category->id]);

        // attach sí que lo añade. El problema es que si volvemos a hacer el patch, vuelve a añadirlo (y no queremos repetidos)
        // $product->categories()->attach([$category->id]);

        // Este sí que agrega la nueva categoría sin eliminar las anteriores. Si hacemos varias veces el patch, no continúa añadiendo la misma varias veces, la mantiene
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('La categoría especificada no es una categoría de este producto', 404);
        }

        // eliminar la categoría
        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);
    }
}
