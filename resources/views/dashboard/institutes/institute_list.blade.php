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
            <big>প্রতিষ্ঠান তালিকাঃ</big>

            @php
                $intitutes = null;
                if(Auth::user()->role=='admin' || Auth::user()->role=='teo'){
                    $institutes = Auth::user()->upazilla->institutes;
                } elseif(Auth::user()->role=='ateo'){
                    $institutes = Auth::user()->institutes;
                }
            @endphp

            @if(count($institutes)>0)
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>প্রতিষ্ঠান</th>
                            <th>ডিভাইস</th>
                            {{--                        <th>প্রস্থান</th>--}}
                            {{--                        <th>অবস্থানকাল</th>--}}
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($institutes as $institute)
                            <tr>
                                <td>
                                    {{ $institute->name }}<br/>
                                </td>
                                <td>
                                    {{ $institute->device_id }}<br/>
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
                <big><b>কোন প্রতিষ্ঠান নেই</b></big>
            @endif
        </div>
    </div>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@stop