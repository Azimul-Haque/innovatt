<html>
<head>
    <title>Report | Download | PDF</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'kalpurush', sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, td, th {
            border: 1px solid black;
        }
        th, td{
            padding: 4px;
            font-family: 'kalpurush', sans-serif;
            font-size: 13px;
        }
        @page {
            header: page-header;
            footer: page-footer;
            background-image: url({{ public_path('images/logo_background.png') }});
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        .bluebackground {
            background: rgba(0,153,204, 0.5);
        }
        .yellowbackground {
            background: rgba(243,156,18, 0.5);
        }
    </style>
</head>
<body>
<h2 align="center">
    <img src="{{ public_path('images/logo.png') }}" style="height: 50px; width: auto;"><br/>
    <span style="font-family: Calibri;">Innova Attendance Management Solution</span>
</h2>
<p align="center" style="padding-top: -20px;">
    <span style="font-size: 20px;">
      {{ $institute->name }}<br/>
       দিন ভিত্তিক রিপোর্ট উপস্থিতি রিপোর্ট ({{ bangla(date('F d, Y', strtotime($start_date)))}} - {{bangla(date('F d, Y', strtotime($end_date))) }})
    </span><br/>
</p>

<div class="" style="padding-top: 0px;">
    <table class="">
        <tr>
            <th class="bluebackground" width="30%">শিক্ষক/ অফিস সহকারি</th>
            <th class="bluebackground" width="27%">প্রবেশ</th>
            <th class="bluebackground" width="27%">প্রস্থান</th>
            <th class="bluebackground">অবস্থানকাল</th>
        </tr>
        @php
            $datearray = [];
            $alldatearray = [];
            $counter = 0;
            foreach($attendances as $attendance) {
              foreach($teachers as $teacher) {
                if(($attendance->device_pin == $teacher->device_pin)) {
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['id'] = $teacher->id;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['name'] = $teacher->name;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['phone'] = $teacher->phone;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['timestampdata'] = $attendance->timestampdata;
                } else {
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['id'] = $teacher->id;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['name'] = $teacher->name;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['phone'] = $teacher->phone;
                    $datearray[date('ymd', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['timestampdata'] = '1970-01-01';
                }
              }
              $datearray[date('ymd', strtotime($attendance->timestampdata))]['date'] = $attendance->timestampdata;
              $counter++;
            }
            for ($i=0; $i<=$daysbetween; $i++) { 
                $alldatearray[$i] = date('Y-m-d', strtotime($start_date. ' + '. $i .' day'));
            }
        @endphp
        @foreach($alldatearray as $dayfromallarray)
            @php
                $atleastteacher = 0;
            @endphp
            @foreach($datearray as $datesingles)
                @if(date('Y-m-d', strtotime($datesingles['date'])) == $dayfromallarray)
                    <tr>
                        <td colspan="4" class="yellowbackground">{{ bangla(date('F d, Y', strtotime($datesingles['date']))) }}</td>
                    </tr>
                    @foreach($datesingles['data'] as $teacher)
                        <tr>
                            <td>
                                {{ count($teacher) }}<br/>
                                {{ reset($teacher)['name'] }}<br/>
                                <small>যোগাযোগঃ <span style="font-family: Calibri;">{{ reset($teacher)['phone'] }}</span></small>
                            </td>
                            @if((count($teacher) == 1) && (date('Y-m-d', strtotime(reset($teacher)['timestampdata'])) == '1970-01-01'))
                                <td align="center">
                                    @php
                                        $inleave = 0;
                                        $reason = '';
                                        foreach ($leaves as $leave) {
                                            if(($leave->teacher_id == reset($teacher)['id']) && ($leave->leave_start <= date('Y-m-d', strtotime($dayfromallarray))) && ($leave->leave_end >= date('Y-m-d', strtotime($dayfromallarray)))) {
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
                                <td align="center"></td>
                                <td align="center"></td>
                            @else
                                <td align="center">{{ bangla(date('F d, Y h:i A', strtotime(reset($teacher)['timestampdata']))) }}</td>
                                <td align="center">
                                    @if((reset($teacher) != end($teacher)) && (date('Y-m-d', strtotime(end($teacher)['timestampdata'])) != '1970-01-01'))
                                        {{ bangla(date('F d, Y h:i A', strtotime(end($teacher)['timestampdata']))) }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if((reset($teacher) != end($teacher)) && (date('Y-m-d', strtotime(end($teacher)['timestampdata'])) != '1970-01-01'))
                                        <span class="badge badge-success">{{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    @php
                        $atleastteacher = 1;
                    @endphp
                @endif
            @endforeach
            @if($atleastteacher == 0)
                <tr>
                    <td colspan="4" class="yellowbackground">{{ bangla(date('F d, Y', strtotime($dayfromallarray))) }}</td>
                </tr>
                @foreach($teachers as $teacher)
                    <tr>
                        <td>
                            {{ $teacher->name }}<br/><small>যোগাযোগঃ <span style="font-family: Calibri;">{{ $teacher->phone }}</span></small>
                        </td>
                        <td align="center">
                            @php
                                $inleave = 0;
                                $reason = '';
                                foreach ($leaves as $leave) {
                                    if(($leave->teacher_id == $teacher->id) && ($leave->leave_start <= date('Y-m-d', strtotime($dayfromallarray))) && ($leave->leave_end >= date('Y-m-d', strtotime($dayfromallarray)))) {
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
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </table>
</div>

<htmlpagefooter name="page-footer">
    <small>ডাউনলোডের সময়কালঃ <span style="font-family: Calibri;">{{ date('F d, Y, h:i A') }}</span></small><br/>
    <small style="font-family: Calibri; color: #6D6E6A;">Generated by: http://innoatt.com (+88 01515297658)</small>
</htmlpagefooter>
</body>
</html>