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
            $counter = 0;
            foreach($attendances as $attendance) {
                $found = false;
              foreach($teachers as $teacher) {
                if(($attendance->device_pin == $teacher->device_pin)) {
                    $found = true;
                    $datearray[date('mdy', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['timestampdata'] = $attendance->timestampdata;
                    $datearray[date('mdy', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['name'] = $teacher->name;
                    $datearray[date('mdy', strtotime($attendance->timestampdata))]['data'][$teacher->id][$counter]['phone'] = $teacher->phone;
                }
              }
              if($found) {
                $datearray[date('mdy', strtotime($attendance->timestampdata))]['date'] = $attendance->timestampdata;
              }
              $counter++;
            }
        @endphp
        @foreach($datearray as $datesingles)
            <tr>
                <td colspan="4" class="yellowbackground">{{ bangla(date('F d, Y', strtotime($datesingles['date']))) }}</td>
            </tr>
            @foreach($datesingles['data'] as $teacher)
                <tr>
                    <td>
                        {{ reset($teacher)['name'] }}<br/><small>যোগাযোগঃ <span style="font-family: Calibri;">{{ reset($teacher)['phone'] }}</span></small>
                    </td>
                    <td align="center">{{ bangla(date('F d, Y h:i A', strtotime(reset($teacher)['timestampdata']))) }}</td>
                    <td align="center">
                        @if(reset($teacher) != end($teacher))
                            {{ bangla(date('F d, Y h:i A', strtotime(end($teacher)['timestampdata']))) }}
                        @endif
                    </td>
                    <td align="center">
                        @if(reset($teacher) != end($teacher))
                            <span class="badge badge-success">{{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>

<htmlpagefooter name="page-footer">
    <small>ডাউনলোডের সময়কালঃ <span style="font-family: Calibri;">{{ date('F d, Y, h:i A') }}</span></small><br/>
    <small style="font-family: Calibri; color: #6D6E6A;">Generated by: http://innoatt.com (+88 01515297658)</small>
</htmlpagefooter>
</body>
</html>