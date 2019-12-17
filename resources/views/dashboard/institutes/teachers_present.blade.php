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
    <div class="row">
        <div class="col-md-8">
            <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F d, Y')) }}</b></big>

            @if(count($presents)>0)
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক</th>
                            <th>প্রতিষ্ঠান</th>
    {{--                        <th>প্রবেশ</th>--}}
    {{--                        <th>প্রস্থান</th>--}}
    {{--                        <th>অবস্থানকাল</th>--}}
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($presents as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher->id) }}">{{ $teacher->name }}</a>
                                    <br/><small><a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ $teacher->phone }}</a></small>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.institute.single', $teacher->institute->device_id) }}">{{ $teacher->institute->name }}</a>
                                    <br/><small> {{ $teacher->institute->device_id }}</small>
                                </td>
    {{--                            <td>{{ bangla(date('F d, Y h:i A', strtotime(reset($teacher)['timestampdata']))) }}</td>--}}
    {{--                            <td>--}}
    {{--                                @if(reset($teacher) != end($teacher))--}}
    {{--                                    {{ bangla(date('F d, Y h:i A', strtotime(end($teacher)['timestampdata']))) }}--}}
    {{--                                @endif--}}
    {{--                            </td>--}}
    {{--                            <td>--}}
    {{--                                @if(reset($teacher) != end($teacher))--}}
    {{--                                    <span class="badge badge-success">{{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}</span>--}}
    {{--                                @endif--}}
    {{--                            </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <br><br><br>
                <big><b>কেউ উপস্থিত নেই</b></big>
            @endif
        </div>
    </div>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@stop