@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('css')

@stop

@section('content_header')
    <h1>
      @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
      {{ Auth::user()->upazilla->upazilla_bangla }}, {{ Auth::user()->upazilla->district_bangla }}
      @endif
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-university"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">মোট প্রতিষ্ঠান</span>
          <span class="info-box-number">{{ bangla(Auth::user()->upazilla->institutes->count()) }} টি</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">মোট শিক্ষক</span>
          <span class="info-box-number">
            @php
              $totalteachersupazilla = 0;
              foreach (Auth::user()->upazilla->institutes as $institute) {
                $totalteachersupazilla = $totalteachersupazilla + $institute->users->count();
              }
            @endphp
            {{ bangla($totalteachersupazilla) }} জন
        </span>
        </div>
      </div>
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">আজকের উপস্থিতি</span>
          <span class="info-box-number">{{ bangla($totalpresenttoday) }} জন</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">আজকের অনুপস্থিতি</span>
          <span class="info-box-number">{{ bangla($totalteachersupazilla - $totalpresenttoday) }} জন</span>
        </div>
      </div>
    </div>
  </div>
  @endif
@stop