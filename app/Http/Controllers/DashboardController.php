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
        $this->middleware('admin')->except('index', 'getInstitutes', 'createInstitute', 'getSingleInstitute', 'storeInstitute', 'editInstitute', 'updateInstitute', 'createUser', 'getSigleUser');
    }

    public function index()
    {
        if(!empty(Auth::user()->institute->device_id)) {
            $attendances = Attendance::where('device_id', Auth::user()->institute->device_id)->get();
        } else {
            $attendances = collect();
        }
        
        return view('dashboard.index')->withAttendances($attendances);
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
          'designation'      => 'required',
          'role'             => 'required',
          'phone'            => 'required|unique:users',
          'device_pin'       => 'required',
          'upazilla_id'      => 'required',
          'institute_id'     => 'required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->designation = $request->designation;
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
        // $institute = Institute::find($id);
        // $upazillas = Upazilla::all();

        // return view('dashboard.institutes.edit')
        //                     ->withInstitute($institute)
        //                     ->withUpazillas($upazillas);
    }

    public function getSigleUser($id)
    {
        $teacher = User::find($id);
        $attendances = Attendance::where('device_pin', $teacher->device_pin)
                                 ->where('device_id', $teacher->institute->device_id)
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
        $institutes = Institute::where('upazilla_id', $id)->paginate(15);

        return view('dashboard.institutes.index')->withInstitutes($institutes);
    }

    public function getInstitutes()
    {
        $institutes = Institute::where('upazilla_id', Auth::user()->upazilla_id)->paginate(15);

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
          'device_id'        => 'required|unique:institutes',
          'upazilla_id'      => 'required'
        ]);

        $institute = new Institute;
        $institute->name = $request->name;
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
          'device_id'        => 'required|unique:institutes,device_id,' . $institute->id,
          'upazilla_id'      => 'required'
        ]);

        $institute->name = $request->name;
        $institute->device_id = $request->device_id;
        $institute->upazilla_id = $request->upazilla_id;
        $institute->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!'); 
        return redirect()->route('dashboard.institutes');
    }

    public function getSingleInstitute($device_id)
    {
        $institute = Institute::where('device_id', $device_id)->first();
        $attendances = Attendance::where('device_id', $device_id)->get();
        $teachers = User::where('institute_id', $institute->id)->get();

        return view('dashboard.institutes.single')
                            ->withInstitute($institute)
                            ->withAttendances($attendances)
                            ->withTeachers($teachers);
    }
}
