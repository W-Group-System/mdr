<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    public function index() {
        $company = Company::get();

        return view('admin.companies', array('company' => $company));
    }

    public function store(Request $request) {
        $company = new Company;
        $company->code = $request->code;
        $company->name = $request->name;
        $company->status = 'Active';
        $company->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id) {
        $company = Company::findOrFail($id);
        $company->code = $request->code;
        $company->name = $request->name;
        $company->save();

        Alert::success('Updated Successfully')->persistent('Dismiss');
        return back();
    }

    public function deactivate($id) {
        $company = Company::findOrFail($id);
        $company->status = 'Inactive';
        $company->save();

        Alert::success('Deactivated Successfully')->persistent('Dismiss');
        return back();
    }

    public function activate($id) {
        $company = Company::findOrFail($id);
        $company->status = 'Active';
        $company->save();

        Alert::success('Activated Successfully')->persistent('Dismiss');
        return back();
    }
}
