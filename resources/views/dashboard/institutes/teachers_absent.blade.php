@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('css')

@stop

@section('content_header')
    <h1>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' )
            {{ Auth::user()->upazilla->upazilla_bangla }}, {{ Auth::user()->upazilla->district_bangla }}
        @endif
    </h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 sm-4 ">
            <big>অনুপস্থিতির/ ছুটির তালিকাঃ <b>{{ bangla(date('F d, Y')) }}</b></big>
            @if(count($absents)>0)
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক</th>
                            <th>প্রতিষ্ঠান</th>
                            <th>অনুপস্থিতি/ ছুটিতে</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($absents as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher->id) }}">{{ $teacher->name }}</a><br/>
                                    <small>
                                        <a href="tel:{{ $teacher->phone }}" title="ফোন করুন">
                                            <i class="fa fa-phone"></i> {{ $teacher->phone }}
                                        </a>
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.institute.single', $teacher->institute->device_id) }}">{{ $teacher->institute->name }}</a>
                                    <br/><small> {{ $teacher->institute->device_id }}</small>
                                </td>
                                <td>
                                    @php
                                        $inleave = 0;
                                        $reason = '';
                                        foreach ($teacher->leaves as $leave) {
                                            if(($leave->leave_start <= date('Y-m-d')) && ($leave->leave_end >= date('Y-m-d'))) {
                                                $inleave = 1;
                                                $reason = $leave->reason;
                                            }
                                        }
                                    @endphp
                                    @if($inleave == 1)
                                        <span style="color: #4D7902;"><b><i class="fa fa-power-off"></i> ছুটিতে ({{ $reason }})</b></span>
                                    @else
                                        <span style="color: #FF0000;"><b><i class="fa fa-exclamation-triangle"></i> অনুপস্থিত</b></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <br><br><br>
                    <big><b>কেউ অনুপস্থিত নেই</b></big>
                @endif
        </div>
    </div>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@stop