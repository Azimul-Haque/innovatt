<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Upazilla;
use App\Institute;
use App\Attendance;
use App\Leave;
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
            if($teacher->leave_start_date != null && $teacher->leave_end_date != null)
            {
                
            } else {
                $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                        ->where('device_id', $teacher->institute->device_id)
                                        ->where('device_pin', $teacher->device_pin)
                                        ->first();
            }
            if (!empty($attendance)) {
                $teachersPresent[] = $teacher->toArray();
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

    public function getInstituteDailyCombinedReport(Request $request, $device_id)
    {
        $date = date('Y-m-d', strtotime($request->query_date));
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", $date)  // teachers der jonno daily data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();
        $teachers = $institute->users;

        $absents = [];
        foreach ($teachers as $teacher){
            $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", $date)
                                    ->where('device_id', $teacher->institute->device_id)
                                    ->where('device_pin', $teacher->device_pin)
                                    ->first();
            if (empty($attendance)) {
                $absents[] = $teacher;
            }
        }

        // get leaves
        $leaves = Leave::where('institute_id', $institute->id)
                       ->where('leave_start', '<=', $date)
                       ->where('leave_end', '>=', $date)
                       ->orderBy('id', 'desc')
                       ->get();
        $pdf = PDF::loadView('dashboard.reports.combined_report', ['querydate' => $date, 'institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers, 'absents' => $absents, 'leaves' => $leaves]);
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
    public function getInstituteQueryReport(Request $request, $device_id)
    {
        // createFromFormat e somossa ache
        $start_date = date('Y-m-d', strtotime($request->query_start_date));
        $end_date = date('Y-m-d', strtotime($request->query_end_date));

        $datetime1 = new Carbon($start_date);
        $datetime2 = new Carbon($end_date);
        $interval = $datetime1->diff($datetime2);
        $daysbetween = $interval->format('%a');
        // dd(date('Y-m-d', strtotime($start_date. ' + 1 day')));

        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), ">=", $start_date)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "<=", $end_date)
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();                        
        $teachers = $institute->users;
        $teachersPresent = $this->getPresentTeachers($teachers);

        $leaves = Leave::where('institute_id', $institute->id)
                       ->where(function($query) use ($start_date, $end_date){
                           $query->where(function($query) use ($start_date, $end_date){
                                     $query->where('leave_start', '>=', $start_date)
                                           ->where('leave_start', '<=', $end_date);
                                 })
                                 ->orWhere(function($query) use ($start_date, $end_date){
                                    $query->where('leave_end', '>=', $start_date)
                                          ->where('leave_end', '<=', $end_date);
                                 });
                       })
                       ->orderBy('id', 'desc')
                       ->get();
                       // dd($attendances);
        $pdf = PDF::loadView('dashboard.reports.institute_query', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers, 'leaves' => $leaves, 'start_date' => $request->query_start_date, 'end_date' => $request->query_end_date, 'daysbetween' => $daysbetween]);
        $fileName = 'Institute_Query_Combined_Report_' . $request->query_start_date . '_' . $request->query_end_date . '_' . $device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }
    public function getTeacherQueryReport(Request $request, $unique_key)
    {
        // createFromFormat e somossa ache
        $start_date = date('Y-m-d', strtotime($request->query_start_date));
        $end_date = date('Y-m-d', strtotime($request->query_end_date));
        $teachers = User::where('unique_key', $unique_key)->get();
//        $teachers = [];
//        $teachers[] = $teacher;
        if($start_date->gt($end_date))
            return redirect()->route('dashboard.user.single', $teachers[0]->id)->with('warning', 'সথিকভাবে দিন প্রবেশ করুন!');
        $institute = $teachers[0]->institute;
        $attendances = Attendance::where('device_id', $institute->device_id)
            ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), ">=", $start_date)
            ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "<=", $end_date)
            ->orderBy('timestampdata', 'asc')
            ->get();
//        dd($teachers[0]);
//        $teachers = $institute->users;
//        dd($teachers);
//        $allTeachers = $this->getAllTeachers();
//        $teachersPresent = $this->getPresentTeachers($teachers);
//
//        $absentTeachers = array_diff($teachers->toArray(), $teachersPresent);
//        dd(bangla(date('F d, Y', strtotime($request->query_start_date))));
        $pdf = PDF::loadView('dashboard.reports.institute_query', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers, 'start_date'=>$request->query_start_date, 'end_date'=>$request->query_end_date]);
        $fileName = 'Teacher_Query_Combined_Report_' . $request->query_start_date . '_' . $request->query_end_date . '_' . $teachers[0]->id .'.pdf';
        return $pdf->download($fileName); // stream
    }
    public function getTeacherMonthlyReport($unique_key){
        $teachers = User::where('unique_key', $unique_key)->get();
        $institute = $teachers[0]->institute;
        $attendances = Attendance::where('device_id', $institute->device_id)
            ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))  // teachers der jonno daily data
            ->orderBy('timestampdata', 'asc')
            ->get();
        $pdf = PDF::loadView('dashboard.reports.institutemonthly', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers]);
        $fileName = 'Teacher_Monthly_Report_'. $institute->device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }
    public function getTeacherYearlyReport($unique_key){
        $teachers = User::where('unique_key', $unique_key)->get();
        $institute = $teachers[0]->institute;
        $attendances = Attendance::where('device_id', $institute->device_id)
            ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y')"), "=", Carbon::now()->format('Y'))  // teachers der jonno daily data
            ->orderBy('timestampdata', 'asc')
            ->get();
        $pdf = PDF::loadView('dashboard.reports.instituteyearly', ['institute' => $institute, 'attendances' => $attendances, 'teachers' => $teachers]);
        $fileName = 'Teacher_Yearly_Report_'. $institute->device_id .'.pdf';
        return $pdf->download($fileName); // stream
    }
}