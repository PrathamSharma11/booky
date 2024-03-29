@extends('front.master')
@section('content')

<section class="page-title-area">
    <div class="container">
    <div class="page-title-content">
    <h1>Welcome to Booky</h1>
    <ul>
    <li><a href="#">Home</a></li>
    <li>Profile Authentication</li>
    </ul>
    </div>
    </div>
    </section>


    <section class="profile-authentication-area ptb-70">
    <div class="container">
    <div class="row">
    <div class="col-lg-6 col-md-12">
    <div class="login-form">
    <h2>Login</h2>
    <form action="{{url('login_verification')}}" method="post">
    @csrf
    <div class="form-group">
    <label>Email</label>
    <input type="text" name="email" class="form-control" placeholder="Username or email">
    </div>
    <div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <div class="row align-items-center">
    <div class="col-lg-6 col-md-6 col-sm-6 remember-me-wrap">
    <p>
    <input type="checkbox" id="test2">
    <label for="test2">Remember me</label>
    </p>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 lost-your-password-wrap">
    <a href="{{url('/forgotpassword')}}" class="lost-your-password">Forgot password?</a>
    </div>
    </div>
    <button type="submit">Log In</button>
     {{-- <a href="{{route('google.login')}}"><button type="button" class="btn btn-danger btn-block">LOGIN WITH GOOGLE</button>
        <i class="fa-brands fa-google"></i>

    </a><br><br> --}}
    </form>
    </div>
    </div>
    <div class="col-lg-6 col-md-12">
    <div class="register-form">
    @if(Session::has('error'))
    <div class="alert alert-sm alert-danger alert-block" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
            <strong>{!!session('error')!!}</strong>
        </button>

    </div>
    @endif
    <h2>Register</h2>
    <form action="user_insert" method="post">
    @csrf
    <div class="form-group">
    <label>Username</label>
    <input type="text" name="name" class="form-control" placeholder="Username or email">
    </div>
    <div class="form-group">
    <label>Email</label>
    <input type="email"name = "email" class="form-control" placeholder="Username or email">
    </div>
    <div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <p class="description">The password should be at least eight characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & )</p>
    <button type="submit">Register</button>
    </form>
    </div>
    </div>
    </div>
    </div>
    </section>


    <section class="facility-area bg-f7f8fa pt-70 pb-40">
    <div class="container">
    <div class="row">
    <div class="col-lg-3 col-sm-6 col-md-3 col-6">
    <div class="single-facility-box">
    <div class="icon">
    <i class="flaticon-free-shipping"></i>
    </div>
    <h3>Free Shipping</h3>
    <p>Free shipping world wide</p>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-md-3 col-6">
    <div class="single-facility-box">
    <div class="icon">
    <i class="flaticon-headset"></i>
    </div>
    <h3>Support 24/7</h3>
    <p>Contact us 24 hours a day</p>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-md-3 col-6">
    <div class="single-facility-box">
    <div class="icon">
    <i class="flaticon-secure-payment"></i>
    </div>
    <h3>Secure Payments</h3>
    <p>100% payment protection</p>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-md-3 col-6">
    <div class="single-facility-box">
    <div class="icon">
    <i class="flaticon-return-box"></i>
    </div>
    <h3>Easy Return</h3>
    <p>Simple returns policy</p>
    </div>
    </div>
    </div>
    </div>
    </section>




@endsection
