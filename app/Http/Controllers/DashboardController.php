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
        $this->middleware('admin')->except('index', 'createAteo', 'storeAteo', 'updateAteo', 'getFemaleTeacherList', 'getMaleTeacherList', 'getAllTeacherList', 'getAllTeacherLateList', 'getAllTeacherEarlyLeaveList', 'getInstituteList','getAteo', 'getUpazillaSchoolsTeachersAbsentListForAteo', 'getUpazillaSchoolsTeachersAbsentList', 'getUpazillaSchoolsTeachersPresentListForAteo', 'getUpazillaSchoolsTeachersPresentList', 'getInstitutes', 'createInstitute', 'getSingleInstitute', 'storeInstitute', 'editInstitute', 'updateInstitute', 'deleteInstitute', 'createInstituteUser', 'storeInstituteUser', 'createUser', 'editUser', 'updateUser', 'deleteUser', 'getSingleUser', 'getPersonalProfile', 'updatePersonalProfile', 'setUpazillaContact');
    }

    public function index()
    {
        $this->updateUserLeaveStatus(User::where('upazilla_id', Auth::user()->upazilla_id)->get());

        if (Auth::user()->role == 'headmaster') {
            return redirect()->route('dashboard.institute.single', Auth::user()->institute->device_id);
        } elseif (Auth::user()->role == 'teacher' || Auth::user()->role == 'officeassistant') {
            return redirect()->route('dashboard.user.single', Auth::user()->id);
        } else {
            $queryTeachers = null;
            if (Auth::user()->role == 'ateo') {
               return redirect()->route('dashboard.upazillas.ateo', Auth::user()->unique_key);
            } else {
                $queryTeachers = User::where('role', 'headmaster')
                                     ->orWhere('role', 'teacher')
                                     ->orWhere('role', 'officeassistant')
                                     ->where('upazilla_id', Auth::user()->upazilla_id)
                                     ->get();
            }
            
            $totalpresenttoday = 0;
            $totallateentrytoday = 0;
            $totalearlyleavetoday = 0;
            foreach ($queryTeachers as $teacher) {
                $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                    ->where('device_id', $teacher->institute->device_id)
                    ->where('device_pin', $teacher->device_pin)
                    ->first();
                if (!empty($attendance)) {
                    $totalpresenttoday++;
                }
                $late = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                    ->where('device_id', $teacher->institute->device_id)
                    ->where('device_pin', $teacher->device_pin)
                    ->where(DB::raw("DATE_FORMAT(timestampdata, '%h:%i')"), ">", date('h-i', strtotime('09:00')))
                    ->first();
                if (!empty($late)) {
                    $totallateentrytoday++;
                }
                $earlies = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                    ->where('device_id', $teacher->institute->device_id)
                    ->where('device_pin', $teacher->device_pin)
                    ->get();
                if (!empty($earlies[1]) && (date('h-i', strtotime($earlies[1]->timestampdata)) < date('h-i', strtotime('15:50')))) {
                    $totalearlyleavetoday++;
                }
            }

            return view('dashboard.index')
                ->withTotalpresenttoday($totalpresenttoday)
                ->withTotallateentrytoday($totallateentrytoday)
                ->withTotalearlyleavetoday($totalearlyleavetoday);
        }
    }

    public function updateUserLeaveStatus($users){
        $today =  Carbon::now();

        foreach ($users as $user){
            if($user->leave_start_date == null || $user->leave_end_date==null) continue;

            $start_date =Carbon::parse( $user->leave_start_date);
            $end_date = Carbon::parse($user->leave_end_date);

            if($today->gt($end_date) || $today->lt($start_date)){
                    $user->leave_start_date = null;
                    $user->leave_end_date = null;
                    $user->save();
            }
        }
    }


    public function getAteo($id)
    {
        $ateo = User::where('unique_key', $id)->get()->first();
        $instituteIds = Institute::where('user_id', $ateo->id)->lists('id');
        $institutes = Institute:: whereIn('id', $instituteIds)->get();
        $queryTeachers = User::where('role', 'headmaster')
            ->orWhere('role', 'teacher')
            ->whereIn('institute_id', $instituteIds)
            ->get();
        $totalTeachers = 0;
        $totalpresenttoday = 0;

        foreach ($institutes as $institute) {
            $totalTeachers += $institute->users->count();
        }


        foreach ($queryTeachers as $teacher) {
            $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                ->where('device_id', $teacher->institute->device_id)
                ->where('device_pin', $teacher->device_pin)
                ->first();
            if (!empty($attendance)) {
                $totalpresenttoday++;

            }
        }


        return view('dashboard.upazillas.ateo')
            ->withAteo($ateo)
            ->withInstitutes($institutes)
            ->withTotalteachers($totalTeachers)
            ->withTotalpresenttoday($totalpresenttoday);
    }


    public function getUsers()
    {
        $admins = User::where('role', 'admin')->get();
        $teos = User::where('role', 'teo')->get();
        $ateos = User::where('role', 'ateo')->get();
        $teachers = User::where('role', 'headmaster')
            ->orWhere('role', 'teacher')
            ->orWhere('role', 'officeassistant')
            ->paginate(10);

        return view('dashboard.users.index')
            ->withAdmins($admins)
            ->withTeos($teos)
            ->withAteos($ateos)
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
    public function createAteo()
    {
        $upazillas = [];
        $institutes = Auth::user()->upazilla->institutes;
        $upazillas[] = Auth::user()->upazilla;
//        dd($upazillas);
        return view('dashboard.users.create')
            ->withInstitutes($institutes)
            ->withUpazillas($upazillas);
    }

    public function storeUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:users',
            'device_pin' => 'required',
            'upazilla_id' => 'required',
            'institute_id' => 'sometimes',
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
        $user->password = Hash::make('secret');
//        dd($user->id);

        if ($request->role != 'ateo'){
            if($request->role == 'teo')
                $user->institute_id = 0;
            else
                $user->institute_id = $request->institute_id[0];
            $user->save();
        }

        else {
            $user->institute_id = 0;
            $user->save();

            foreach ($request->institute_id as $institute) {
                $ateoInstitute = Institute::find($institute);
                $ateoInstitute->user_id = $user->id;
                $ateoInstitute->save();
//                dd($ateoInstitute);
            }
        }

        Session::flash('success', 'সফলভাবে যোগ করা হয়েছে!');
        return redirect()->route('dashboard.users');
    }


    public function storeAteo(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:users',
            'upazilla_id' => 'required',
            'institute_id' => 'sometimes',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->gender = $request->gender; // 1 = Male, 2 = Female
        $user->unique_key = generate_token(100);
        $user->role = $request->role;
        $user->type = $request->role;
        $user->phone = $request->phone;
        $user->email = $request->phone . '@innovaatt.com';
        $user->device_pin = 0;
        $user->upazilla_id = $request->upazilla_id;
        $user->password = Hash::make('secret');
//        dd($user->id);

        if ($request->role != 'ateo'){
            if($request->role == 'teo')
                $user->institute_id = 0;
            else
                $user->institute_id = $request->institute_id[0];
            $user->save();
        }

        else {
            $user->institute_id = 0;
            $user->save();

            foreach ($request->institute_id as $institute) {
                $ateoInstitute = Institute::find($institute);
                $ateoInstitute->user_id = $user->id;
                $ateoInstitute->save();
//                dd($ateoInstitute);
            }
        }

        Session::flash('success', 'সফলভাবে যোগ করা হয়েছে!');
        return redirect()->route('dashboard.institutes');
    }

    public function editUser($id)
    {
        $teacher = User::find($id);
        $ateoInstituteIds = Institute::where('user_id', $id)->lists('id');
        $ateoInstitutes = Institute::where('user_id', $id)->get();
        $institutes = Institute::where('upazilla_id', $teacher->upazilla_id)
                                ->whereNotIn('id', $ateoInstituteIds)
                                ->get();
        $upazillas = Upazilla::all();

//        dd($institutes);

//        if($teacher->role=='ateo'){
//            $institutes = array_diff($institutes[0], $ateoInstitutes[0]);
//        }

        return view('dashboard.users.edit')
            ->withTeacher($teacher)
            ->withInstitutes($institutes)
            ->withAteoinstitutes($ateoInstitutes)
            ->withUpazillas($upazillas);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'device_pin' => 'required',
            'upazilla_id' => 'required',
            'institute_id' => 'sometimes',
            'password' => 'sometimes'
        ]);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->device_pin = $request->device_pin;
        $user->upazilla_id = $request->upazilla_id;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
//        dd($request->leave_start_date);


        if( $request->leave_start_date!= '' ||  $request->leave_end_date!= ''){
            $start_date = Carbon::createFromFormat('F d, Y', $request->leave_start_date);
            $end_date = Carbon::createFromFormat('F d, Y', $request->leave_end_date);
            
            $user->leave_start_date = $start_date;
            $user->leave_end_date = $end_date;
        } else{
            $user->leave_start_date = null;
            $user->leave_end_date = null;
        }

        if ($request->role != 'ateo'){
            if($request->role == 'teo') {
                $user->institute_id = 0;
            } else {
                $user->institute_id = $request->institute_id[0];
            }
        } else {
            foreach ($request->institute_id as $institute) {
                $ateoInstitute = Institute::find($institute);
                $ateoInstitute->user_id = $user->id;
                $ateoInstitute->save();
            }
        }

        $user->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
        if (Auth::user()->role == 'admin') {
            return redirect()->route('dashboard.users');
        } else {
            return redirect()->route('dashboard.institute.single', $user->institute->device_id);
        }
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();

        Session::flash('success', 'সফলভাবে ডিলেট করা হয়েছে!');
        return redirect()->back();
    }


    public function updateAteo(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'upazilla_id' => 'required',
            'institute_id' => 'sometimes',
            'password' => 'sometimes'
        ]);

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->upazilla_id = $request->upazilla_id;
        $user->leave_start_date = null;
        $user->leave_end_date = null;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
//        dd($request->leave_start_date);



        if ($request->role != 'ateo'){
            if($request->role == 'teo')
                $user->institute_id = 0;
            else
                $user->institute_id = $request->institute_id[0];
            $user->save();
        }

        else {
            $user->institute_id = 0;
            $user->save();

            foreach ($request->institute_id as $institute) {
                $ateoInstitute = Institute::find($institute);
                $ateoInstitute->user_id = $user->id;
                $ateoInstitute->save();
//                dd($ateoInstitute);
            }
        }

        $user->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
        return redirect()->route('dashboard.institutes');

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
        $ateos = User::where('role', 'ateo')->where('upazilla_id', $id)->get();

        return view('dashboard.institutes.index')->withInstitutes($institutes)->withAteos($ateos);
    }

    public function getAllTeachers()
    {
        $allTeachers = [];
        $queryTeachers = null;


        if (Auth::user()->role == 'admin' || Auth::user()->role == 'teo') {
//            $queryTeachers = User::where('role', 'headmaster')
//                ->orWhere('role', 'teacher')
//                ->where('upazilla_id', Auth::user()->upazilla_id)
//                ->get();
            foreach (Auth::user()->upazilla->institutes as $institute) {
                foreach($institute->users as $user){
                    $allTeachers[] = $user;
                }
            }

        } else if (Auth::user()->role == 'headmaster') {
//            $queryTeachers = User::where('role', 'headmaster')
//                ->orWhere('role', 'teacher')
//                ->where('institute_id', Auth::user()->institute_id)
//                ->get();
            foreach (Auth::user()->institutes->users as $user) {
                    $allTeachers[] = $user;

            }
        }
//         elseif (Auth::user()->role == 'ateo') {
//
//            $instituteIds = Institute::where('user_id', Auth::user()->id)->lists('id');
//            $institutes = Institute::whereIn('id', $instituteIds)->get();
//            foreach($institutes as $institute){
//                $queryTeachers = $institute->users();
//                foreach ($queryTeachers as $teacher) {
//                    $allTeachers[] = $teacher;
//                }
//            }
//
//        }

//        foreach ($queryTeachers as $teacher) {
//            $allTeachers[] = $teacher;
//        }

//        dd($totalteachersupazilla);
        return $allTeachers;
    }

    public function getAllTeachersForAteo($id)
    {
        $allTeachers = [];
        $instituteIds = Institute::where('user_id', $id)->lists('id');
        $institutes = Institute:: whereIn('id', $instituteIds)->get();
//        $queryTeachers = User::where('role', 'headmaster')
//            ->orWhere('role', 'teacher')
//            ->whereIn('institute_id', $instituteIds)
//            ->get();


        foreach ($institutes as $institute) {
            foreach ($institute->users as $teacher) {
                $allTeachers[] = $teacher;
            }
        }
        return $allTeachers;
    }


    public function getPresentTeachers()
    {
        $teachersPresent = [];
        $queryTeachers = $this->getAllTeachers();

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

    public function getPresentTeachersForAteo($id)
    {
        $teachersPresent = [];
        $queryTeachers = $this->getAllTeachersForAteo($id);

        foreach ($queryTeachers as $teacher) {

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


    public function getUpazillaSchoolsTeachersPresentList()
    {
        $teachersPresent = $this->getPresentTeachers();

        return view('dashboard.institutes.teachers_present')->withPresents($teachersPresent);
    }

    public function getUpazillaSchoolsTeachersPresentListForAteo($id)
    {
        $ateo = User::where('unique_key', $id)->get()->first();

        $teachersPresent = $this->getPresentTeachersForAteo($ateo->id);

        return view('dashboard.institutes.teachers_present')->withPresents($teachersPresent);
    }


    public function getUpazillaSchoolsTeachersAbsentList()
    {
        $allTeachers = $this->getAllTeachers();
        $teachersPresent = $this->getPresentTeachers();

        $absentTeachers = array_diff($allTeachers, $teachersPresent);
        return view('dashboard.institutes.teachers_absent')->withAbsents($absentTeachers);
    }

    public function getUpazillaSchoolsTeachersAbsentListForAteo($id)
    {
        $ateo = User::where('unique_key', $id)->get()->first();
//        dd($ateo);
        $allTeachers = $this->getAllTeachersForAteo($ateo->id);
        $teachersPresent = $this->getPresentTeachersForAteo($ateo->id);

        $absentTeachers = array_diff($allTeachers, $teachersPresent);
        return view('dashboard.institutes.teachers_absent')->withAbsents($absentTeachers);
    }


    public function getInstitutes()
    {
        $institutes = Institute::where('upazilla_id', Auth::user()->upazilla_id)->get(); //paginate(15);
        $ateos = User::where('role', 'ateo')->where('upazilla_id', Auth::user()->upazilla_id)->get();

        return view('dashboard.institutes.index')->withInstitutes($institutes)->withAteos($ateos);
    }

    public function createInstitute()
    {
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.create')->withUpazillas($upazillas);
    }

    public function storeInstitute(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'serial' => 'required',
            'device_id' => 'required|unique:institutes',
            'upazilla_id' => 'required'
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
            'name' => 'required',
            'serial' => 'required',
            'device_id' => 'required|unique:institutes,device_id,' . $institute->id,
            'upazilla_id' => 'required'
        ]);
        
        $old_device_id = $institute->device_id;
        
        $institute->name = $request->name;
        $institute->serial = $request->serial;
        $institute->device_id = $request->device_id;
        $institute->upazilla_id = $request->upazilla_id;
        $institute->save();

        // update the attendances
        $attendances = Attendance::where('device_id', $old_device_id)->get();
        foreach ($attendances as $attendance) {
            $attendance->device_id = $request->device_id;
            $attendance->save();
        }
        // update the attendances

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
        return redirect()->route('dashboard.institutes');
    }

    public function deleteInstitute(Request $request, $id)
    {
        $institute = Institute::find($id);

        foreach ($institute->users as $user) {
            $user->delete();
        }
        $institute->delete();

        Session::flash('success', 'সফলভাবে ডিলেট করা হয়েছে!');
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
        $absents = [];
        foreach ($teachers as $queryTeacher){
            $attendance = Attendance::where(DB::raw("DATE_FORMAT(timestampdata, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                    ->where('device_id', $queryTeacher->institute->device_id)
                                    ->where('device_pin', $queryTeacher->device_pin)
                                    ->first();
            if (empty($attendance)) {
                $absents[] = $queryTeacher;
            }
        }
//        dd($absents);


        return view('dashboard.institutes.single')
            ->withInstitute($institute)
            ->withAbsents($absents)
            ->withAttendances($attendances)
            ->withTeachers($teachers);
    }

    public function getInstituteList(){
        return view('dashboard.institutes.institute_list');
    }
    public function getFemaleTeacherList(){
        return view('dashboard.institutes.teachers_female');
    }
    public function getMaleTeacherList(){
        return view('dashboard.institutes.teachers_male');
    }
    public function getAllTeacherList(){
        return view('dashboard.institutes.teachers');
    }
    public function getAllTeacherLateList(){
        return view('dashboard.institutes.teachers_late');
    }
    public function getAllTeacherEarlyLeaveList(){
        return view('dashboard.institutes.teachers_earlyLeave');
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
            'name' => 'required',
            'gender' => 'required',
            'role' => 'required',
            'phone' => 'required|unique:users',
            'device_pin' => 'required',
            'upazilla_id' => 'required',
            'institute_id' => 'required'
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
            'name' => 'required',
            'gender' => 'required',
            'password' => 'required'
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
        return redirect()->route('dashboard.personal.profile');
    }

    public function updateUpazilla(Request $request, $id) 
    {
        $this->validate($request, [
            'entrance'   => 'required',
            'departure'   => 'required',
            'contact'    => 'required'
        ]);
        
        $upazilla = Upazilla::find($id);
        $upazilla->entrance = date('H:i:s', strtotime($request->entrance));
        $upazilla->departure = date('H:i:s', strtotime($request->departure));
        $upazilla->contact = $request->contact;
        $upazilla->save();

       Session::flash('success', 'সফলভাবে হালনাগাদ করা হয়েছে!');
       return redirect()->route('dashboard.index');
    }
}
