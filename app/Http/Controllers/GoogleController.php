<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Laravel\Socialite\Facades\Socialite;


class GoogleController extends Controller
{
    public function provider(){
        return Socialite::driver('google')->redirect();


    }
    public function callbackHandle(){
        $user = Socialite::driver('google')->user();
        $data = User::where('email',$user->email)->first();
        if(is_null($data)){
            $users['name']=$user->name;
            $users['email']=$user->email;
            $data = User::create($users);
        }
        Auth::login($data);
        return redirect('/');
    }
}
