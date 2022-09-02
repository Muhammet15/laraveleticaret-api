<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
         $credentials = $request->only(["email","password"]);
         $rememberMe = $request->get("remember-me",false);
         if(Auth::attempt($credentials,$rememberMe))
         {// return Auth::user();
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
        $token = $user->createToken("ecommerce"); //->plainText();
        $data=["user"=>$user->name,
        "tokenable_id"=>$user->user_id,
        "plaintext_token"=>$token->plainTextToken];
        return response($data,201);
    }


    public function logout(Request $request)
    {
      $bearerToken = $request->bearerToken();
      $token =PersonalAccessToken::findToken($bearerToken);
      $token->delete();
      return response(["message","çıkış yapıldı."]);
      // $user = Auth::user()->token();
      // $user->revoke();
      // return 'logged out';
      // Auth::logout();
      // return "oldu";
      //   if (Auth::check()) {
      //     Auth::user()->getRememberToken()->revoke();
      //     return response()->json(['success' =>'Successfully logged out of application'],200);
      // }else{
      //     return response()->json(['error' =>'api.something_went_wrong'], 500);
      // }
   }
    //  return Hash::check(Auth::user());
    //   if( auth()->guest() ) {
    //     return response()->json([
    //     'name' => 'Name',
    //     ]);
    // }
    //  return Auth::guard('web');
    // //  ->delete();
    //   // Auth::logout();
    //   // return Redirect::to("/giris");
//     }

}
