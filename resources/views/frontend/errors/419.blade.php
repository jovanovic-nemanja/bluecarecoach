@extends('layouts.app')

@section('content')

<link href="{{ asset('new/product/Products.css') }}" rel="stylesheet">

<div class="row" style="text-align: center!important; padding-top: 2%; padding-bottom: 2%;">
  <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
    <h1 style="font-size: 6rem;"><strong style="color: red;">419</strong></h1>
    <h5>The page has expired due to inactivity.<br/><br/> Please refresh and try again.</h5><br>
    <div>
      <a href="{{ url('/') }}" class="btn btn-danger">Home</a>   
    </div>
  </div>
</div>

<!-- join us -->
<section class="u-clearfix u-palette-2-base u-section-2" id="carousel_8033">
  <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
    <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
      <div class="u-layout">
        <div class="u-layout-row">
          <div class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-container-style u-layout-cell u-left-cell u-size-34 u-layout-cell-1">
            <div class="u-container-layout u-container-layout-1">
              <h3 class="u-text u-text-1">Join us today</h3>
              <p style="color: #fff;" class="u-text u-text-default u-text-2">Become a member of our importer-exportter community and get to feel tools meant for the business world of tomorow.</p>
            </div>
          </div>
          <div class="u-align-left u-container-style u-layout-cell u-right-cell u-size-26 u-layout-cell-2">
            <div class="u-container-layout">
              @guest
                <a href="{{ route('emailverify') }}" class="u-border-2 u-border-white u-btn u-button-style u-palette-3-base u-btn-1" target="_blank">Sign Up to Buy</a>
                <a href="{{ route('emailverifyseller') }}" class="u-border-2 u-border-white u-btn u-button-style u-palette-1-base u-btn-2" target="_blank">Sign Up to Sell</a>
              @endguest
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@stop
