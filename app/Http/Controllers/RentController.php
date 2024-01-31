<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function rent(){
        $product=Product::latest()->get();
        return view('front.rent',compact('product'));

    }
    public function rent2(){
        return view('front.rent2');
    }
}
