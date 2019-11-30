@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তালিকা')

@section('css')

@stop

@section('content_header')
    <h1>
      সকল প্রতিষ্ঠান
      <div class="pull-right">
        <a href="{{ route('dashboard.institutes.create') }}" class="btn btn-success" title="নতুন প্রতিষ্ঠান যোগ করুন"><i class="fa fa-plus"></i> প্রতিষ্ঠান যোগ</a>
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin')
    <div class="row">
      <div class="col-md-6">
        <big>অ্যাডমিনগণ</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>নাম</th>
                <th>মোবাইল</th>
                <th>কার্যক্রম</th>
              </tr>
            </thead>
            <tbody>
              @foreach($admins as $admin)
                <tr>
                  <td>{{ $admin->name }}</td>
                  <td>{{ $admin->phone }}</td>
                  <td>
                    <a href="{{ route('dashboard.users.edit', $admin->id) }}" class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <big>শিক্ষা অফিসার/ অনুমোদিত কর্তৃপক্ষ</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>নাম</th>
                <th>মোবাইল</th>
                <th>কার্যক্রম</th>
              </tr>
            </thead>
            <tbody>
              @foreach($teos as $teo)
                <tr>
                  <td>{{ $teo->name }}</td>
                  <td>{{ $teo->phone }}</td>
                  <td>
                    <a href="{{ route('dashboard.users.edit', $teo->id) }}" class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@stop