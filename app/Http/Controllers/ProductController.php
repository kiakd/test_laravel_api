<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    //
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            "name"=> "sometimes|required|max:255|string",
            "file_image"=>"nullable",
        ]);
        $new_nameImage = null;
        if($request->hasFile("file_image")){
            $file = $request->file("file_image");
            $new_nameImage = rand().".".$file->getClientOriginalExtension();
            $file->move(public_path("/updates/images"), $new_nameImage);
        }
        $product = Product::create([
            "name"=> $validate['name'],
            "file_image"=> $new_nameImage,
        ]);
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $validate = $request->validate([
            "name"=> "sometimes|required|max:255|string",
            "file_image"=>"sometimes|nullable",
        ]);
        if(!$product->image){
            $new_nameImage = null;
        }
        if($request->hasFile("file_image")){
            $file = $request->file("file_image");
            $path = "updates/images/";
            $new_nameImage = $path.time().".".$file->getClientOriginalExtension();
            $file->move(public_path($path), $new_nameImage);
            if(File::exists($product->file_image))
            {
                File::delete($product->file_image);
            }
        }
        $product->update([
            "name"=> $validate['name'],
            "file_image"=> $new_nameImage,
        ]);
        return new ProductResource($product);

    }

    public function destroy(Product $product)
    {
        $product->delete();
        if(File::exists($product->file_image))
            {
                File::delete($product->file_image);
            }
        return response()->noContent();
    }


}
