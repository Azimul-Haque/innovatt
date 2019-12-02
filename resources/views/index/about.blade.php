@extends('layouts.index')
@section('title')
    About Us
@endsection

@section('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/stylesheet.css') }}"> --}}
@endsection

@section('content')
    <!-- head section -->
    <section class="content-top-margin page-title page-title-small bg-gray">
      <div class="container">
          <div class="row">
              <div class="col-lg-8 col-md-7 col-sm-12 wow fadeInUp" data-wow-duration="300ms">
                  <!-- page title -->
                  <h1 class="black-text">About</h1>
                  <!-- end page title -->
              </div>
              <div class="col-lg-4 col-md-5 col-sm-12 breadcrumb text-uppercase wow fadeInUp xs-display-none" data-wow-duration="600ms">
                  <!-- breadcrumb -->
                  <ul>
                     {{--  <li><a href="{{ route('index.index') }}">Home</a></li>
                      <li><a href="#">About</a></li> --}}
                  </ul>
                  <!-- end breadcrumb -->
              </div>
          </div>
      </div>
    </section>

    <!-- content section -->
    <section class="bg-black wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-8 text-center center-col">
                    <div class="about-year text-uppercase white-text"><span class="clearfix">2</span> Years</div>
                    <p class="title-small letter-spacing-1 white-text font-weight-100">Open since 2018, we're digital
                        natives with a pioneering approach to open-source development, IoT based system installation, Attendance and Fingerprint Device Solutions etc.</p>
                </div>
            </div>
        </div>
    </section>   
@endsection

@section('js')
   
@endsection