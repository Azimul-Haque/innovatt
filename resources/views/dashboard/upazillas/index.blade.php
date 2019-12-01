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
            <th>উপজেলা</th>
            <th>জেলা</th>
            <th>প্রতিষ্ঠান সংখ্যা</th>
            <th>শিক্ষক সংখ্যা</th>
          </tr>
        </thead>
        <tbody>
          @foreach($upazillas as $upazilla)
            <tr>
              <td><a href="{{ route('dashboard.upazillas.schools', $upazilla->id) }}" title="প্রতিষ্ঠান তালিকা দেখুন">{{ $upazilla->upazilla_bangla }}</a></td>
              <td>{{ $upazilla->district_bangla }}</td>
              <td>{{ $upazilla->institutes->count() }}</td>
              <td>
                @php
                  $totalteachersupazilla = 0;
                  foreach ($upazilla->institutes as $institute) {
                    $totalteachersupazilla = $totalteachersupazilla + $institute->users->count();
                  }
                @endphp
                {{ bangla($totalteachersupazilla) }} জন
              </td>
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