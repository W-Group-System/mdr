<?php

namespace App\Http\Controllers\Approver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApproverDashboardController extends Controller
{
    public function index() {

        return view('approver.dashboard');
    }
}
