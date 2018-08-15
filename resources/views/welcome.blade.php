@extends('layouts.app')

@section('styles')
<style>
    h1
    {
        color:#FFFFFF;
    }

    h2
    {
        color:#FFCC00;
    }

     hr
    {
        background-color: #505050;
    }

    p
    {
        color: #D3D3D3;
    }

     .card
     {
         border-color: #181818;
     }

     .card-pricing
     {
         border-radius: 1px solid;
         border-color: #ffffff;

     }
     .card-body-pricing
     {
         padding: 15px !important;
     }
     .card-body
     {
        padding: 15px 0px 0px 0px;
        margin: 0px 0px 0px 0px;
     }

     .fas, .far
     {
         font-size: 24px;
         padding: 20px;
     }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <img src="{{asset('images/banners/img-2.jpg')}}" class="img-fluid" />
        </div>
    </div>
    
    <div class="row">
    
        <div class="col-12">
        <br />
            <h1>Welcome to Paperclip SA</h1>
        <br />
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
        <h2>Who are we?</h2>

        <div class="card">
 
                <img class="card-img-top" src="{{asset('images/football-690684_640.jpg')}}" alt="Who are We">
                <div class="card-body">
                    <p class="card-text">We’re a live and on-demand Sports Action streaming provider.</p>
                    <p class="card-text">We give you live and on-demand sports action from club, school and match events.</p>
                    <a href="/contact" class="btn btn-outline-danger float-right mt-4">Contact Us</a>
                </div>
        </div>

        </div>
        <div class="col-12 col-md-6">
        <h2>How does it work?</h2>

        <div class="card">
 
                <div class="card-body">
                    <p class="card-text">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-search"></i>&nbsp;<a href="/signup">Register</a> now and find your sports channel</li>
                            <li><i class="fas fa-mouse-pointer"></i>&nbsp;&nbsp;&nbsp;Choose a game</li>
                            <li><i class="far fa-credit-card"></i>Buy credit or subscribe to a channel</li>
                            <li><i class="fas fa-play"></i>&nbsp;Enjoy your favourite sport streams</li>
                        </ul>
                    </p>
                    <p class="card-text">
                        It’s that easy...
                    </p>
                    <p class="card-text">
                        Whether you are a sports star or it's your mates from the club, or just keeping tabs on the latest action
                    </p>

                </div>
        </div>

        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-12">
        <br />
            <h2>Pricing</h2>
            <br />
            <p>Find a pricing plan that works for you.</p>

        <div class="card-deck mb-3 text-center">
        <div class="card mb-4 box-shadow card-pricing">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Credits</h4>
          </div>
          <div class="card-body card-body-pricing">
            <h1 class="card-title pricing-card-title">R50+</h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Purchase unlimitted access to </li>
              <li>individual  Live and On-demand videos</li>
              <li>@ R5+ per video</li>
              <li>depending on the channel</li>
            </ul>
            <a href="/buy" class="btn btn-lg btn-block btn-outline-warning">Buy Credit</a>
          </div>
        </div>
        <div class="card mb-4 box-shadow card-pricing">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Single Channel</h4>
          </div>
          <div class="card-body card-body-pricing">
            <h1 class="card-title pricing-card-title">R30 <small class="text-muted">/ mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>A monthly paid subscription</li>
              <li>Access to all videos</li>
              <li>from your selected channel</li>
              <li>Live and On-demand</li>
            </ul>
            <a href="/subscription/checkout" class="btn btn-lg btn-block btn-warning">Get started</a>
          </div>
        </div>
        <div class="card mb-4 box-shadow card-pricing">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Full Access</h4>
          </div>
          <div class="card-body card-body-pricing">
            <h1 class="card-title pricing-card-title">R60 <small class="text-muted">/ mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>A monthly paid subscription</li>
              <li>Access to all videos</li>
              <li>from selected channels</li>
              <li>Full Access to Live and On-demand</li>
            </ul>
            <a href="/subscription/checkout" class="btn btn-lg btn-block btn-warning">Get Started</a>
          </div>
        </div>
        </div>
      </div>
    </div>

    <a href="http://new.supabets.co.za/Sport/Default.aspx?promocode=actionreplay">
        <img src="{{asset('/storage/adverts/images/239SqTl5NMJBWQ3RdeHXmcNnOMTZf8oaVQ26Q1bJ.jpeg')}}" class="img-fluid" />
    </a>
</div>


@endsection

