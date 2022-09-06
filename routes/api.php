<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AddressController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\UserController as FrontendUserController;
use App\Models\ProductImage;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//public route
Route::group(["prefix"=>"v1"],function(){

Route::get('/home', [HomeController::class,'index'])->name('profile');
Route::get('/kategori', [FrontendCategoryController::class,'index']);
Route::post('/kategori/{slug}', [FrontendCategoryController::class,'getCategory']);

Route::post('/giris',[AuthController::class,'signIn'])->name('girispost');
Route::post('/uye-ol',[AuthController::class,'signUp'])->name('uyeolpost');
Route::get('/cikis',[AuthController::class,'logout'])->name('logout');
//private route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware"=>"auth:sanctum"],function()
{
    Route::get('/sepetim',[CartController::class,'index']); 
    Route::post('/sepete-ekle',[CartController::class,'add']);
    Route::delete('/sepete-sil',[CartController::class,'remove'])->name('remove');

});
Route::group(["middleware"=>"auth:sanctum"],function()
{
    Route::get('user/',[UserController::class,'index'])->name('user.index');
    Route::get('user/{user_id}',[UserController::class,'destroy'])->whereNumber('user_id')->name('user.destroy');
    Route::resource('user',UserController::class);
    
    Route::get('adres/{user_id}/address',[AddressController::class,'index'])->name('address.index');
    Route::get('adres/{user_id}/address/{address_id}',[AddressController::class,'destroy'])->whereNumber('address_id')->name('address.destroy');
    Route::resource('adres/{user_id}/address',AddressController::class);
    
    Route::get('/categories/{category_id}',[CategoryController::class,'destroy'])->whereNumber('category_id')->name('categories.destroy');
    Route::resource('/categories',CategoryController::class);
    
    Route::get('/products/{products_id}',[ProductController::class,'destroy'])->whereNumber('products_id')->name('products.destroy');
    Route::resource('/products',ProductController::class);
    
    Route::get('products/{product}/images/{images_id}',[ProductImageController::class,'destroy'])->whereNumber('images_id')->name('images.destroy');
    Route::resource('/products/{product}/images',ProductImageController::class);
    // Route::get('/adres/{user_id}/address',[AddressController::class,'index'])->name("adres.create");
});
});