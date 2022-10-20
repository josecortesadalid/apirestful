<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
// use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        // $this->middleware('transform.input' . ProductTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-products')->except('index');
    }
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read-general') || request()->user()->tokenCan('manage-products')) {
            $products = $seller->products;
            return $this->showAll($products);
        }

        throw new AuthenticationException();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller) 
    // Hemos puesto que recibimos un user en vez de un seller. 
    //Esto lo hacemos por si hay alguien que no es seller pero que pretende publicar por primera vez
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];
        $this->validate($request, $rules);
        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $today = today()->format('Y-m-d');
        $data['image'] = $request->image->store($today); // laravel automáticamente sabe que es un archivo y nos da acceso a determinados métodos, como store
        // el primer parámetro del store es la ruta, pero como la tenemos por defecto lo dejamos vacío, el segundo parámetro es el disco, lo tenemos por defecto así que no lo ponemos
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in: ' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE, 
            'image' => 'image',
        ];
        $this->validate($request, $rules);

        $this->verificarVendedor($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));
        if ($request->has('status')) { 
            $product->status = $request->status; // podemos recibir estatus disponible o no disponible

            if ($product->estaDisponible() && $product->categories()->count() == 0) { // si se ha dicho que el producto está disponible pero no tiene categoría
                $this->errorResponse('Un producto disponible debe tener al menos una categoría', 409);
            }
        }
        if ($request->hasFile('image')) { 
            Storage::delete($product->image); // borra la imagen anterior
            $today = today()->format('Y-m-d');
            $product->image = $request->image->store($today); // guarda la nueva
        }
        if($product->isClean()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        $product->save();
        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verificarVendedor($seller, $product);
        Storage::delete($product->image);
        $product->delete();
        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}
