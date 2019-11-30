<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\User;
use App\Upazilla;
use App\Institute;

use Carbon\Carbon;
use DB, Hash, Auth, Image, File, Session;
use Purifier;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('admin')->except('getInstitutes', 'createInstitute');
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function getUsers()
    {
        $users = User::all(); // alada korte hobe type wise

        return view('dashboard.users.index');
    }

    public function getUpazillas()
    {
        $upazillas = Upazilla::paginate(20);

        return view('dashboard.upazillas')->withUpazillas($upazillas);
    }

    public function getInstitutes()
    {
        $institutes = Institute::paginate(20);

        return view('dashboard.institutes.index')->withInstitutes($institutes);
    }

    public function createInstitute()
    {
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.create')->withUpazillas($upazillas);
    }

    public function storeInstitute(Request $request)
    {
        $upazillas = Upazilla::all();

        return view('dashboard.institutes.create')->withUpazillas($upazillas);
    }
}
