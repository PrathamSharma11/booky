@extends('front.master')
@section('content')

<div class="jumbotron text-center">
    <h1 class="display-3">Thank You!</h1>
    {{-- <div class = "container">
        <table class="table table-bordered text-center bg-info">
                <tr>
                    <th colspan="4">Your Items</th>
                </tr>
                <tr>

                    <th>Username</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Total</th>

                </tr>

                <tr>

                    <td>{{$order->name}}</td>
                    <td>{{$order->address}}</td>
                    <td>{{$order->phone_num}}</td>
                    <td>{{$order->grand_total}}</td>

                </tr>

        </table>

        <table class="table table-bordered text-center bg-info">
            <tr>
                <th colspan="4">Your Items</th>
            </tr>
            <tr>

                <th>Order id</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>

            </tr>
            @foreach($items as $i)
            <tr>

                <td>{{$i->order_id}}</td>
                <td>{{$i->product_name}}</td>
                <td>{{$i->product_price}}</td>
                <td>{{$i->product_quantity}}</td>

            </tr>
            @endforeach
    </table>






        </div> --}}
    <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your account setup.</p>
    <hr>
    <p>
      Having trouble? <a href="#">Contact us</a>
    </p>
    <p class="lead">
      <a class="btn btn-primary btn-sm" href="{{url("/")}}" role="button">Continue to homepage</a>
    </p>
  </div>


@endsection
