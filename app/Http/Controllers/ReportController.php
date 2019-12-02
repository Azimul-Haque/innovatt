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

    
}
