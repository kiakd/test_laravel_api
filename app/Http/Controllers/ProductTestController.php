<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductTestController extends Controller
{
    //
    public function ProductTest()
    {
        $product = Product::orderBy('id');
        if(request()->has('search'))
        {
            // dd($product);
            $product = $product->where('name','like','%'. request()->search .'%')
            ->orWhere('pathFileName', 'like', '%'. request()->search .'%');
        }
        // $product->paginate(3);
        return response()->json($product->paginate(3));
    }
}
