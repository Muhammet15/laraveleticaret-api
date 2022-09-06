<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CategoryController extends Controller
{
    public function index(Request $request){
        return $categories = Category::where('is_active',1)->get();
    //    return  $categories = Category::all()->where("is_active",true);
        // $products =  Category::get()->category;
        // $products =  Category::find(1)->category;
        // return $products;
        // $data=[
        //     'categories'=>$categories,
        //     // 'products'=>$products
        // ];
        if ($categories->count()==0) {
             return response($categories,404);
        }
        return response($categories);
        // return view('frontend.home.index',compact("categories","products"));
    }
    public function getCategory(Request $request)
    {
       
        $slug = $request->get("slug");
        $category = Category::all()->where("slug",$slug)->first();
        $categorys= $category->category;
        if ($category==null) return response($category,404);
        $data=[
            'category'=>$category,
        ];
        return response($data,200);
    }
}
