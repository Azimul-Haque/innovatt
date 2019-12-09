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
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ক্রমিক</th>
            <th>নাম</th>
            <th>উপজেলা</th>
            <th>ডিভাইস আইডি (SN)</th>
            <th>শিক্ষক সংখ্যা</th>
            <th>কার্যক্রম</th>
          </tr>
        </thead>
        <tbody>
          @foreach($institutes as $institute)
            <tr>
              <td>{{ $institute->serial }}</td>
              <td><a href="{{ route('dashboard.institute.single', $institute->device_id) }}">{{ $institute->name }}</a></td>
              <td>{{ $institute->upazilla->upazilla_bangla }}, {{ $institute->upazilla->district_bangla }}</td>
              <td>{{ $institute->device_id }}</td>
              <td>{{ bangla($institute->users->count()) }} জন</td>
              <td>
                <a href="{{ route('dashboard.institute.single', $institute->device_id) }}" class="btn btn-info btn-sm" title="প্রতিষ্ঠান বৃত্তান্ত দেখুন"><i class="fa fa-eye"></i> বিস্তারিত</a>
                <a href="{{ route('dashboard.institutes.edit', $institute->id) }}" class="btn btn-success btn-sm" title="প্রতিষ্ঠান সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div>
        {{-- {{ $institutes->links() }} --}}
      </div>
    </div>
  @endif
@stop