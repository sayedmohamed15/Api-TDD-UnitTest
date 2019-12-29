<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection as ProductCollection;

class ProductController extends Controller
{
    //
    public function index(){

        return new ProductCollection(Product::paginate());

    }

    public function store(Request $request){
//        return response()->json([],201);
        $data = $this->validateRequest($request);
        $product=Product::firstOrCreate($data);
//        return redirect($product->path());
        return response()->json(new ProductResource($product),201);

    }
    public function show($id){
        $product=Product::findOrfail($id);
        return response()->json(new ProductResource($product));


    }
    public function update(Request $request, $id){
        $product=Product::findOrfail($id);
//        $data = $this->validateRequest($request);
//        $product=$product->update($data);
        $product->update([
            'name'=>$request->name,
            'slug'=>str_slug($request->name),
            'price'=>$request->price
        ]);
        return response()->json(new ProductResource($product));


    }
    public function destroy($id){
        $product=Product::findOrfail($id);
        $product->delete();
        return response()->json(null,204);


    }
    public function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
        ]);
    }
}
