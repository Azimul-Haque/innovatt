@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তথ্য')

@section('css')

@stop

@section('content_header')
    <h1>
      {{ $institute->name }}
      <div class="pull-right">
        {{-- <a href="{{ route('dashboard.institutes.create') }}" class="btn btn-success" title="নতুন প্রতিষ্ঠান যোগ করুন"><i class="fa fa-plus"></i> প্রতিষ্ঠান যোগ</a> --}}
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'headmaster')
    <div class="row">
      <div class="col-md-4">
        <big>শিক্ষক তালিকা (মোটঃ {{ bangla($institute->users->count()) }} জন)</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>শিক্ষক</th>
                <th>পদবি</th>
              </tr>
            </thead>
            <tbody>
              @foreach($institute->users as $teacher)
                <tr>
                  <td>{{ $teacher->name }}<br/><small>{{ $teacher->phone }}</small></td>
                  <td>{{ $teacher->designation }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@stop