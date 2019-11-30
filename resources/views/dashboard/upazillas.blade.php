@extends('adminlte::page')

@section('title', 'উপজেলা তালিকা')

@section('css')

@stop

@section('content_header')
    <h1>সকল উপজেলা</h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin')
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>জেলা</th>
            <th>উপজেলা</th>
            <th>প্রতিষ্ঠান সংখ্যা</th>
            <th>শিক্ষক সংখ্যা</th>
          </tr>
        </thead>
        <tbody>
          @foreach($upazillas as $upazilla)
            <tr>
              <td>{{ $upazilla->district_bangla }}</td>
              <td>{{ $upazilla->upazilla_bangla }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div>
        {{ $upazillas->links() }}
      </div>
    </div>
  @endif
@stop