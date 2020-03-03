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

            @if(count($presents) > 0)
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক</th>
                            <th>প্রতিষ্ঠান</th>
                            <th>প্রবেশ</th>
                            <th>প্রস্থান</th>
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
                                    <br/><small>ডিভাইস আইডিঃ {{ $teacher->institute->device_id }}</small>
                                </td>
                                @php
                                    foreach ($attendances as $attendance) {
                                        if($teacher->institute->device_id == $attendance->device_id && $teacher->device_pin == $attendance->device_pin)
                                        $teacheratt[] = $attendance;
                                    }
                                @endphp
                                <td>
                                    @if(!empty($teacheratt[0]))
                                        {{ bangla(date('F d, Y h:i A', strtotime($teacheratt[0]->timestampdata))) }}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($teacheratt[1]))
                                        {{ bangla(date('F d, Y h:i A', strtotime($teacheratt[1]->timestampdata))) }}
                                    @endif
                                </td>
                                @php
                                    $teacheratt = [];
                                @endphp
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