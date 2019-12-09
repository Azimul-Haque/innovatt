<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\User;
use App\Upazilla;
use App\Institute;
use App\Attendance;

use Carbon\Carbon;
use DB, Hash, Auth, Image, File, Session;
use Purifier;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('admin')->except('index', 'getInstitutes', 'createInstitute', 'getSingleInstitute', 'storeInstitute', 'editInstitute', 'updateInstitute', 'createInstituteUser', 'storeInstituteUser', 'createUser', 'editUser', 'updateUser', 'getSingleUser', 'getPersonalProfile', 'updatePersonalProfile');
    }

    public function index()
    {   
        if(Auth::user()->role == 'headmaster') {
            return redirect()->route('dashboard.institute.single', Auth::user()->institute->device_id);
        } elseif(Auth::user()->role == 'teacher') {
            return redirect()->route('dashboard.user.single', Auth::user()->id);
        } else {
            $teachers = User::where('role', 'headmaster')
                            ->orWhere('role', 'teacher')
                            ->where('upazilla_id', Auth::user()->upazilla_id)
                            ->get();
            $totalpresenttoday = 0;
            $totallateentrytoday = 0;
            $totalearlyleavetoday = 0;
            foreach ($teachers as $teacher) {
                $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                         ->where('device_id', $teacher->institute->device_id)
                                         ->where('device_pin', $teacher->device_pin)
                                         ->first();
                if(!empty($attendance)) {
                    $totalpresenttoday++;
                }
                $late = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                         ->where('device_id', $teacher->institute->device_id)
                                         ->where('device_pin', $teacher->device_pin)
                                         ->where(DB::raw("DATE_FORMAT(timestampdata, '%h:%i')"), ">", date('h-i', strtotime('09:00')))
                                         ->first();
                if(!empty($late)) {
                    $totallateentrytoday++;
                }
                $earlies = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                         ->where('device_id', $teacher->institute->device_id)
                                         ->where('device_pin', $teacher->device_pin)
                                         ->get();
                if(!empty($earlies[1]) && (date('h-i', strtotime($earlies[1]->timestampdata)) < date('h-i', strtotime('15:50')))) {
                    $totalearlyleavetoday++;
                }
            }
            return view('dashboard.index')
                        ->withTotalpresenttoday($totalpresenttoday)
                        ->withTotallateentrytoday($totallateentrytoday)
                        ->withTotalearlyleavetoday($totalearlyleavetoday);
        }
    }

    public function getUsers()
    {
        $admins = User::where('role', 'admin')->get();
        $teos = User::where('role', 'teo')->get();
        $teachers = User::where('role', 'headmaster')
                        ->orWhere('role', 'teacher')
                        ->paginate(10);

        return view('dashboard.users.index')
                        ->withAdmins($admins)
                        ->withTeos($teos)
                        ->withTeachers($teachers);
    }

    public function createUser()
    {
        $institutes = Institute::all();
        $upazillas = Upazilla::all();

        return view('dashboard.users.create')
                            ->withInstitutes($institutes)
                            ->withUpazillas($upazillas);
    }

    public function storeUser(Request $request)
    {
        $this->validate($request, [
          'name'             => 'required',
          'gender'           => 'required',
          'role'             => 'required',
          'phone'            => 'required|unique:users',
          'device_pin'       => 'required',
          'upazilla_id'      => 'required',
          'institute_id'     => 'required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->gender = $request->gender; // 1 = Male, 2 = Female
        $user->unique_key = generate_token(100);
        $user->role = $request->role;
        $user->type = $request->role;
        $user->phone = $request->phone;
        $user->email = $request->phone . '@innovaatt.com';
        $user->device_pin = $request->device_pin;
        $user->upazilla_id = $request->upazilla_id;
        $user->institute_id = $request->institute_id;
        $user->password = Hash::make('secret');
        $user->save();

        Session::flash('success', 'সফলভাবে যোগ করা হয়েছে!'); 
        return redirect()->route('dashboard.users');
    }

    public function editUser($id)
    {
        $teacher = User::find($id);
        $institutes = Institute::all();
        $upazillas = Upazilla::all();

        return view('dashboard.users.edit')
                            ->withTeacher($teacher)
                            ->withInstitutes($institutes)
                            ->withUpazillas($upazillas);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
          'name'             => 'required',
          'gender'      => 'required',
          'role'             => 'required',
          'phone'            => 'required|unique:users,phone,' . $user->id,
          'device_pin'       => 'required',
          'upazilla_id'      => 'required',
          'institute_id'     => 'required',
          'password'         => 'required'
        ]);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->device_pin = $request->device_pin;
        $user->upazilla_id = $request->upazilla_id;
        $user->institute_id = $request->institute_id;
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
        if(Auth::user()->role == 'admin') {
            return redirect()->route('dashboard.users');
        } else {
            return redirect()->route('dashboard.institute.single', $user->institute->device_id);
        }
    }

    public function getSingleUser($id)
    {
        $teacher = User::find($id);
        $attendances = Attendance::where('device_pin', $teacher->device_pin)
                                 ->where('device_id', $teacher->institute->device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m')"), "=", Carbon::now()->format('Y-m')) // teacher er jonno monthly data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();

        return view('dashboard.users.single')
                            ->withTeacher($teacher)
                            ->withAttendances($attendances);
    }

    public function getUpazillas()
    {
        $upazillas = Upazilla::withCount('institutes')->orderBy('institutes_count', 'desc')->paginate(15);

        return view('dashboard.upazillas.index')->withUpazillas($upazillas);
    }

    public function getUpazillaSchools($id)
    {
        $institutes = Institute::where('upazilla_id', $id)->get(); //paginate(15);

        return view('dashboard.institutes.index')->withInstitutes($institutes);
    }

    public function getInstitutes()
    {   
        $institutes = Institute::where('upazilla_id', Auth::user()->upazilla_id)->get(); //paginate(15);

        return view('dashboard.institutes.index')->withInstitutes($institutes);
    }

    public function createInstitute()
    {
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.create')->withUpazillas($upazillas);
    }

    public function storeInstitute(Request $request)
    {
        $this->validate($request, [
          'name'             => 'required',
          'serial'             => 'required',
          'device_id'        => 'required|unique:institutes',
          'upazilla_id'      => 'required'
        ]);

        $institute = new Institute;
        $institute->name = $request->name;
        $institute->serial = $request->serial;
        $institute->device_id = $request->device_id;
        $institute->upazilla_id = $request->upazilla_id;
        $institute->save();

        Session::flash('success', 'সফলভাবে যোগ করা হয়েছে!'); 
        return redirect()->route('dashboard.institutes');
    }

    public function editInstitute($id)
    {
        $institute = Institute::find($id);
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.edit')
                            ->withInstitute($institute)
                            ->withUpazillas($upazillas);
    }

    public function updateInstitute(Request $request, $id)
    {
        $institute = Institute::find($id);

        $this->validate($request, [
          'name'             => 'required',
          'serial'             => 'required',
          'device_id'        => 'required|unique:institutes,device_id,' . $institute->id,
          'upazilla_id'      => 'required'
        ]);

        $institute->name = $request->name;
        $institute->serial = $request->serial;
        $institute->device_id = $request->device_id;
        $institute->upazilla_id = $request->upazilla_id;
        $institute->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!'); 
        return redirect()->route('dashboard.institutes');
    }

    public function getSingleInstitute($device_id)
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)
                                 ->where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))  // teachers der jonno daily data
                                 ->orderBy('timestampdata', 'asc')
                                 ->get();
        $teachers = User::where('institute_id', $institute->id)->get();

        return view('dashboard.institutes.single')
                            ->withInstitute($institute)
                            ->withAttendances($attendances)
                            ->withTeachers($teachers);
    }

    public function createInstituteUser($device_id)
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $institutes = Institute::all();
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.createuser')
                            ->withInstitute($institute)
                            ->withInstitutes($institutes)
                            ->withUpazillas($upazillas);
    }

    public function storeInstituteUser(Request $request)
    {
        $this->validate($request, [
          'name'             => 'required',
          'gender'           => 'required',
          'role'             => 'required',
          'phone'            => 'required|unique:users',
          'device_pin'       => 'required',
          'upazilla_id'      => 'required',
          'institute_id'     => 'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->unique_key = generate_token(100);
        $user->role = $request->role;
        $user->type = $request->role;
        $user->phone = $request->phone;
        $user->email = $request->phone . '@innovaatt.com';
        $user->device_pin = $request->device_pin;
        $user->upazilla_id = $request->upazilla_id;
        $user->institute_id = $request->institute_id;
        $user->password = Hash::make('secret');
        $user->save();


        Session::flash('success', 'সফলভাবে যোগ করা হয়েছে!'); 
        return redirect()->route('dashboard.institute.single', $request->device_id);
    }

    public function getPersonalProfile()
    {
        $institutes = Institute::all();
        $upazillas = Upazilla::all();

        return view('dashboard.users.profile')
                            ->withInstitutes($institutes)
                            ->withUpazillas($upazillas);
    }

    public function updatePersonalProfile(Request $request, $id)
    {
        $this->validate($request, [
          'name'             => 'required',
          'gender'      => 'required',
          'password'         => 'required'
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!'); 
        return redirect()->route('dashboard.personal.profile');
    }
}
