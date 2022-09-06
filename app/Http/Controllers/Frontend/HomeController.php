<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    public function index(){
        
        $products = Product::all()->where("is_active",true);
        $categories = Category::all()->where("is_active",true);
        if($products == null && $categories ==null)
        {
            return response(["message" => "sonuc bulunamadı"],404);
        }
        $data = [
            "products"=>$products,
            "categories"=>$categories
        ];
        
        return response ($data);
    }
}
