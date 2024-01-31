<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\RentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('admin/dashboard',[AdminController::class,'index']);


//Admin CategoryController
Route::get('/admin/category',[CategoryController::class,'index']);
Route::post('/admin/category/save',[CategoryController::class,'save']);
Route::get('/admin/category/',[CategoryController::class,'display']);
Route::get('/admin/category/edit/{id}',[CategoryController::class,'edit']);
Route::post('/admin/category/update',[CategoryController::class,'update']);
Route::get('/admin/category/delete/{id}',[CategoryController::class,'delete']);


//Admin ProductController
Route::get('admin/product',[ProductController::class,'index']);
Route::post('/admin/product/save',[ProductController::class,'save']);
Route::get('/admin/product/',[ProductController::class,'display']);
Route::get('/admin/product/edit/{id}',[ProductController::class,'edit']);
Route::post('/admin/product/update',[ProductController::class,'update']);
Route::get('/admin/product/delete/{id}',[ProductController::class,'delete']);

//AdminSlider Controller
Route::get('admin/slider',[SliderController::class,'index']);
Route::post('/admin/slider/save',[SliderController::class,'save']);
Route::get('/admin/slider/',[SliderController::class,'display']);
Route::get('/admin/slider/edit/{id}',[SliderController::class,'edit']);
Route::post('/admin/slider/update',[SliderController::class,'update']);
Route::get('/admin/slider/delete/{id}',[SliderController::class,'delete']);



//Front Controller
Route::get('/',[FrontController::class,'index']);
Route::get('/product_detail/{id}',[FrontController::class,'product_detail']);
Route::post('/cart',[FrontController::class,'cart']);
Route::get('/add_to_cart',[FrontController::class,'add_to_cart']);
Route::get('/cart/delete/{id}',[FrontController::class,'delete']);
Route::post('/place_order',[FrontController::class,'place_order']);
Route::get('/thanks',[FrontController::class,'thanks']);



//Userlogin
Route::get('/login',[UserController::class,'login']);
Route::post('/user_insert',[UserController::class,'user_insert']);
Route::post('/login_verification',[UserController::class,'login_verification']);
Route::get('/logout',[UserController::class,'logout']);
Route::post('/update',[UserController::class,'update']);
Route::get('/display',[UserController::class,'display']);
Route::post('/changePassword',[UserController::class,'changePassword'])->name('changePassword');



//checkout
Route::get('/checkout',[FrontController::class,'checkout']);


//googlelogin
Route::get('google/login',[GoogleController::class,'provider'])->name('google.login');
Route::get('google/callback',[GoogleController::class,'callbackHandel'])->name('google.login.callback');

//Forgotpassword
Route::get('/forgotpassword',[ForgotController::class,'forgot']);


//rent
Route::get('/rentitnow',[RentController::class,'rent']);
Route::get('/rent2itnow',[RentController::class,'rent2']);


//html only
Route::get('/contact',[FrontController::class,'contact']);
Route::get('/privacy',[FrontController::class,'privacy']);
Route::get('/terms',[FrontController::class,'terms']);
Route::get('/customer',[FrontController::class,'customer']);
Route::get('/about',[FrontController::class,'about']);


//paytm
Route::post('/paytm-callback',[FrontController::class,'paytmCallback']);
