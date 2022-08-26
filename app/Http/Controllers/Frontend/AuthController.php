<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class AuthController extends Controller
{
    public function signIn(Request $request)
    {
         $credentials = $request->only(["email","password"]);
         $rememberMe = $request->get("remember-me",false);
         if(Auth::attempt($credentials,$rememberMe))
         {// return Auth::user();
          // return Auth::user();
            $token =Auth::user()->createToken("ecommerce")->plainTextToken; //postman üzerinden çalışıyor.
            $data=["user"=>Auth::user(),
            "token"=>$token];
            return response($data,201);

         }
         else {
            return response(["message"=>"Giris yapamadınız bilgileri kontrol edin"]);
         }
    }
    public function signUp(Request $request)
    {
        $data =  $request->post();
        $data['is_active']=true;
        $data['password'] = bcrypt($request->password);
        $user=  User::create($data);
        $token = $user->createToken("ecommerce")->plainTextToken;
        $data=["tokenable_id"=>$user->user_id,
        "token"=>$token];
        return response($data,201);
    }
    public function logout()
    {
      Auth::user()->getRememberToken()->delete();
      // Auth::logout();
      return Redirect::to("/giris");
    }
}
