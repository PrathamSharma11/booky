@extends('front.master')


<section>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

        </div>

    </div>

</div>
<br><br><br>
</section>



<section class="products-area pb-40">
    <div class="container">
        <div class="section-title">
            <h2>Products</h2>
        </div>
        <div class="products-slides owl-carousel owl-theme">
            @foreach ($product as $p)
            <div class="single-products-box">

                <div class="image">
                    <a href="{{url('/product_detail/'.$p->id)}}" class="d-block"><img
                            src="{{url('upload/'.$p->product_image)}}" alt="image"></a>
                    <div class="new">New</div>
                    <div class="buttons-list">

                    </div>
                </div>
                <div class="content">
                    <h3><a href="{{url('/product_detail/'.$p->id)}}">{{$p->product_name}}</a></h3>
                    <div class="price">
                        <span class="new-price">Rs.{{$p->product_price}}</span>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</section>









