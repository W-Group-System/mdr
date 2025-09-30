<?php

namespace App\Http\Controllers\Admin;

use App\Admin\TimelinessSetup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ComputationController extends Controller
{
    public function timeliness_index() {
        $timeliness_setup_list = TimelinessSetup::get();
        $user =  User::where('status', "Active")->get();
        return view('admin.timeliness_setup',
            array(
                'user' => $user,
                'timeliness_setup_list' => $timeliness_setup_list,
            )
        );
    }

    public function addTimelinessSetup(Request $request) {
        $timeliness_setup = new TimelinessSetup();
        $timeliness_setup->score = $request->score;
        $timeliness_setup->effective_date = date('Y-m-d', strtotime($request->effective_date));
        $timeliness_setup->created_by = auth()->user()->id;
        $timeliness_setup->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

}
