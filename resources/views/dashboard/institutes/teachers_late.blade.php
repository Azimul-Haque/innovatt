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
        <div class="col-md-12">
            <big>বিলম্বিত প্রবেশ তালিকাঃ</big>

            @php
                use App\Attendance;
                    $allLateTeachers = [];
                    if(Auth::user()->role=='admin' || Auth::user()->role=='teo'){
                        $intitutes = Auth::user()->upazilla->institutes;
                        $deviceids = Auth::user()->upazilla->institutes->lists('device_id');
                    } elseif(Auth::user()->role=='ateo'){
                        $intitutes = Auth::user()->institutes;
                        $deviceids = Auth::user()->institutes->lists('device_id');
                    }
                    foreach ($intitutes as $institute) {
                        $teachers = $institute->users;
                        foreach ($teachers as $teacher) {

                            $late = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                              ->where('device_id', $teacher->institute->device_id)
                                              ->where('device_pin', $teacher->device_pin)
                                              ->where(DB::raw("DATE_FORMAT(timestampdata, '%h:%i')"), ">", date('h-i', strtotime($teacher->institute->entrance)))
                                              ->first();
                            if (!empty($late)) {
                                            $allLateTeachers[] = $teacher;
                            }
                        }
                    }
                    $todaysattendances = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                                   ->whereIn('device_id', $deviceids)
                                                   ->orderBy('timestampdata', 'asc')
                                                   ->get();
            @endphp

            @if(count($allLateTeachers)>0)
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক</th>
                            <th>প্রতিষ্ঠান</th>
                            <th>বিলম্বিত প্রবেশের সময়</th>
                            <th>প্রতিষ্ঠানে প্রবেশের নির্ধারিত সময়</th>
                            <th>মোট বিলম্ব</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($allLateTeachers as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher->id) }}">{{ $teacher->name }}</a>
                                    <br/><small><a href="tel:{{ $teacher->phone }}"
                                                                       title="ফোন করুন"><i
                                                    class="fa fa-phone"></i> {{ $teacher->phone }}</a></small>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.institute.single', $teacher->institute->device_id) }}">{{ $teacher->institute->name }}</a>
                                    <br/><small> {{ $teacher->institute->device_id }}</small>
                                </td>
                                @php
                                    foreach ($todaysattendances as $attendance) {
                                        if($attendance->device_id == $teacher->institute->device_id && $attendance->device_pin == $teacher->device_pin) {
                                            $teacherlate[] = $attendance;
                                        }
                                    }
                                @endphp
                                <td>
                                    @if(!empty($teacherlate[0]))
                                        {{ bangla(date('F d, Y h:i A', strtotime($teacherlate[0]->timestampdata))) }}
                                    @endif
                                </td>
                                <td>
                                    {{ bangla(date('F d, Y h:i A', strtotime($teacher->institute->entrance))) }}
                                </td>
                                <td>
                                    @if(!empty($teacherlate[0]))
                                        {{ bangla(Carbon::parse($teacherlate[0]->timestampdata)->diffForHumans(Carbon::parse($teacher->institute->entrance))) }}
                                    @endif
                                </td>
                                @php
                                    $teacherlate = [];
                                @endphp
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <br><br><br>
                <big><b>কোন বিলম্বিত প্রবেশ নেই</b></big>
            @endif
        </div>
    </div>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@stop