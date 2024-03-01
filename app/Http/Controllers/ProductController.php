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
    public function index(){
       return new ProductCollection(Product::all());
    }

    public function show(Product $product){
        return new ProductResource($product);
    }

    public function store(Request $request){
        $request->validate([
            "name"=> "required|max:255|string",
            "pathFileName"=> "sometimes|max:255"
        ]);
        if($request->buffer)
        {
            $productImage = $this->uploadImage($request);
        }
        else
        {
            $productImage = (['file_image'=> null]);
        }
        $product = Product::create([
            "name"=> $request->name,
            "pathFileName" => $productImage['file_image'],
        ]);
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product){
        $request->validate([
            "name"=> "sometimes|required|max:255|string",
            "pathFileName"=> "sometimes|max:255|string"
        ]);
        if($request->buffer)
        {
            $productImage = $this->uploadImage($request, $product->pathFileName);
        }
        else
        {
            $productImage = (['file_image'=> $product->pathFileName]);
        }
        $product->update([
            "name"=> $request->name,
            "pathFileName"=> $productImage['file_image']
        ]);
        return new ProductResource($product);
    }

    public function destroy(Product $product){
        $product->delete();
        return response()->json(["message"=> "success delete", "status"=> 200],200);
    }

    public function uploadImage($datas, $pathFileImageBefore)
    {
        // buffer type and filename
        $buffer = $datas->buffer;
        $arrayBuffer = $buffer["data"];
        $filename = 'image_' . time() . $datas->pathFileName;
        // convert arraybuffer to string by implode
        // use  base64_encode
        $imageData = base64_encode(implode('', array_map(function($e){
            return pack("C*", $e);
        }, $arrayBuffer)));
        // map path
        $path = "uploads/images/";
        if(File::exists($pathFileImageBefore) && $pathFileImageBefore)
            {
                File::delete($pathFileImageBefore);
            }
        // save image to directory public/uploads/images
        file_put_contents(public_path($path).$filename, base64_decode($imageData));
        $product = ([
            'name'=> 'Product'.$filename,
            'file_image'=> $path.$filename,
        ]);
        return $product;
    }

}
