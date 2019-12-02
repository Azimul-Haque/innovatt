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
    	return $pdf->stream($fileName); // stream
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
    	$fileName = 'Institute_Daily_Report_'. $device_id .'.pdf';
    	return $pdf->stream($fileName); // stream
    }
}
