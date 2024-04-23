<?php

namespace App\Http\Controllers\DeptHead;

use App\DeptHead\MdrStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MdrStatusController extends Controller
{
    public function index() {

        $user = Auth::user();

        $mdrStatusList = MdrStatus::where('department_id', $user->department_id)->get();

        return view('dept-head.mdr-status', 
            array(
                'mdrStatusList' => $mdrStatusList
            )
        );
    }
}
