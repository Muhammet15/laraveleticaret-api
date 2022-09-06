<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Product;

class CartController extends Controller
{ 
    public function index(Cart $category){
        $cart = $this->getOrCreateCart();
        if(Auth::user()->user_id===$cart->user_id)
        { 
            $carts = CartDetails::with('product')->where('cart_id',$cart->cart_id)->get();
            return response(["cart"=>$carts]);
        }
        else{ 
            return view("frontend.user.index",compact('cart'));
        }
    }
    public function add(Request $request, int $quantity=1){
        $cart = $this->getOrCreateCart();
        $product_id = $request->product_id; 
        $quantity = $request->quantity;
            $details = new CartDetails(
                [
                    'cart_id'=>$cart->cart_id,
                    'products_id'=>$product_id,
                    'quantity'=>$quantity,
                ]);
        try {
            $details->save();
            $data = [
                "cart"=>$cart,
                "details"=>$details
            ];

        } catch (\Throwable $th) {
            return response(["message"=>"yanlislik var"]);
            
        }
            return response($data);
    }
    private function getOrCreateCart() :Cart
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(
            ['user_id'=>$user->user_id],
            ['code'=>Str::random(length:8)]
        );
        return $cart;
    }

    private function getOrDetailCart()
    {
        $user = Auth::user();
        $cart_detail = CartDetails::all();
        return $cart_detail;
    }
    public function remove(Request $request){
        CartDetails::find($request->cart_detail_id)->delete();
        $cart_details = $this->getOrDetailCart();
        $cart = $this->getOrCreateCart();
        $data = [ 
        'cart_details'=>$cart_details,
        'cart'=>$cart,
        'user'=>Auth::user()
        ];
        return response($data);
        // ->delete();
    }
  
}
