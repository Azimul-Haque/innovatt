@extends('adminlte::page')

@section('title', 'Microfinance Management')

@section('css')

@stop

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="{{ route('dashboard.staffs') }}">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-user-circle-o"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Staff</span>
            <span class="info-box-number">List</span>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="{{ route('dashboard.groups') }}">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-address-card"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Group</span>
              <span class="info-box-number">Names</span>
            </div>
          </div>
      </a>
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="{{ route('dashboard.loanandsavingnames') }}">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-tags"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Loan, Saving</span>
              <span class="info-box-number">& Scheme Names</span>
            </div>
          </div>
      </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="{{ route('olddata.index') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-address-book-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Old Data</span>
              <span class="info-box-number">Entry</span>
            </div>
          </div>
      </a>
    </div>
  </div>
  @endif
@stop