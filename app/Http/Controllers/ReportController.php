<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Upazilla;
use App\Institute;
use App\Attendance;

use Carbon\Carbon;
use DB, Hash, Auth, Image, File, Session;
use Purifier;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }



    public function getPresentTeachers($totalTeachers){
        $teachersPresent = [];
        $queryTeachers = $totalTeachers;

        foreach ($queryTeachers as $teacher) {

            if($teacher->leave_start_date!=null && $teacher->leave_end_date!=null) continue;

            $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                ->where('device_id', $teacher->institute->device_id)
                ->where('device_pin', $teacher->device_pin)
                ->first();
            if (!empty($attendance)) {
                $teachersPresent[] = $teacher;
            }
        }
        return $teachersPresent;
    }


    public function getInstituteDailyReport($device_id) 
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))  // teachers der jonno daily data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();
        $teachers = User::where('institute_id', $institute->id)->get();

        $pdf = PDF::loadView('dashboard.reports.institutedaily', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers]);
        $fileName = 'Institute_Daily_Report_'. $device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }

    public function getInstituteDailyCombinedReport($device_id)
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
            ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))  // teachers der jonno daily data
            ->orderBy('timestampdata', 'asc')
            ->get();
        $teachers = $institute->users;
//        $allTeachers = $this->getAllTeachers();
        $teachersPresent = $this->getPresentTeachers($teachers);

        $absentTeachers = array_diff($teachers->toArray(), $teachersPresent);
//        dd($absentTeachers);


        $pdf = PDF::loadView('dashboard.reports.combined_report', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers, 'absents'=>$absentTeachers]);
        $fileName = 'Institute_Daily_Combined_Report_'. $device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }



    public function getInstituteMonthlyReport($device_id) 
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))  // teachers der jonno daily data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();
        $teachers = User::where('institute_id', $institute->id)->get();

        $pdf = PDF::loadView('dashboard.reports.institutemonthly', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers]);
        $fileName = 'Institute_Monthly_Report_'. $device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }

    public function getInstituteYearlyReport($device_id) 
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y')"), "=", Carbon::now()->format('Y'))  // teachers der jonno daily data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();
        $teachers = User::where('institute_id', $institute->id)->get();

        $pdf = PDF::loadView('dashboard.reports.instituteyearly', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers]);
        $fileName = 'Institute_Yearly_Report_'. $device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }
}
