<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $products = $this->product;
        $prodRep = new ProductRepository($products);

        if ($request->has('conditions')) {
            $prodRep->selectCoditions($request->get('conditions'));
        }

        if ($request->has('fields')) {
            $prodRep->selectFilter($request->get('fields'));
        }

        //$products = $this->product->paginate(10) -- response()->json($products);
        return new ProductCollection($prodRep->getResult()->paginate(10)) ;
    }

    public function show($id)
    {
        $product = $this->product->find($id);
        return response()->json($product);
        //return new ProductResource($product);
    }

    public function save(ProductRequest $request)
    {
        $data = $request->all();
        $product = $this->product->create($data);
        return response()->json($product);
    }

    public function update(ProductRequest $request)
    {
        $data = $request->all();
        $product = $this->product->find($data['id']);
        $product->update($data);
        return response()->json($product);
    }

    public function delete($id)
    {
        $product = $this->product->find($id);
        $product->delete();
        return response()->json(['data' => ['msg' => 'Produto foi removido com sucesso!']]);
    }
}
