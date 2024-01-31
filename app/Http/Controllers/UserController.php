<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function login(){
        return view('front.login');

    }
    public function user_insert(Request $request){

        $user_count=User::where('email',$request->email)->count();
        if($user_count>0){
            return redirect()->back()->with('error','email is already exit');
        }
        else{

            $data=new User();
            $data->name=$request->name;
            $data->email=$request->email;
            $data->password=bcrypt($request->password);
            $data->save();
            if($data){
                return redirect('/login');
            }

        }




    }
    public function login_verification(request $request){
        // echo'<pre>';
        // print_r($request->all());
        $session_id=Session::getId();
        // echo $session_id;
        // die;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            Cart::where('session_id',$session_id)->update(['user_email'=>$request->email]);
            return redirect('/add_to_cart');
        }
        else{
            return redirect()->back();
        }







    }

    public function logout(){
        Auth::logout();
        return redirect('/');

    }

    public function update(Request $a){
        // echo '<pre>';
        // print_r($a->all());


        // $data->password=$a->password;
        // echo '<pre>';
        // print_r($a->all());
        // die;
        $data=User::find($a->id);
        $data->password=$a->password;
        $data->save();
        if($data){
            return redirect('/');
        }
    }

    public function display(){
        $items=Order_item::all();
        // echo'<pre>';
        // print_r($data);
        // die;
        return view('front.master',compact('items'));
    }



     public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");

    }









}

