@extends('adminlte::page')

@section('title', 'ব্যাবহারকারী তালিকা')

@section('css')

@stop

@section('content_header')
    <h1>
        সকল ব্যাবহারকারী
        <div class="pull-right">
            <a href="{{ route('dashboard.users.create') }}" class="btn btn-success"
               title="নতুন ব্যাবহারকারী যোগ করুন"><i class="fa fa-plus"></i> ব্যাবহারকারী যোগ</a>
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
                            <th width="15%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->phone }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.edit', $admin->id) }}"
                                       class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i>
                                        সম্পাদনা</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <big>শিক্ষা অফিসার/ অনুমোদিত কর্তৃপক্ষ</big>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>নাম</th>
                            <th>মোবাইল</th>
                            <th width="15%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teos as $teo)
                            <tr>
                                <td>{{ $teo->name }}</td>
                                <td>{{ $teo->phone }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.edit', $teo->id) }}"
                                       class="btn btn-success btn-sm"
                                       title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <big>ATEO</big>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>নাম</th>
                            <th>মোবাইল</th>
                            <th width="15%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ateos as $ateo)
                            <tr>
                                <td>{{ $ateo->name }}</td>
                                <td>{{ $ateo->phone }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.edit', $ateo->id) }}"
                                       class="btn btn-success btn-sm"
                                       title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <big>শিক্ষক/ অফিস সহকারি তালিকা</big>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>শিক্ষক/ অফিস সহকারি</th>
                            <th>পদবি</th>
                            <th>প্রতিষ্ঠানের নাম</th>
                            <th>উপজেলা</th>
                            <th width="15%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->name }}<br/><small>{{ $teacher->phone }}</small></td>
                                <td>{{ designation($teacher->role) }}</td>
                                <td>{{ $teacher->institute->name }}</td>
                                <td>{{ $teacher->upazilla->upazilla_bangla }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.edit', $teacher->id) }}"
                                       class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i>
                                        সম্পাদনা</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $teachers->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop