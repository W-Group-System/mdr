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
        $company->status = 1;
        $company->save();

        Alert::success('SUCCESS', 'Added Successfully.');
        return back();
    }

    public function update(Request $request, $id) {
        $company = Company::findOrFail($id);
        $company->code = $request->code;
        $company->name = $request->name;
        $company->save();

        Alert::success('SUCCESS', 'Updated Successfully.');
        return back();
    }

    public function deactivate($id) {
        $company = Company::findOrFail($id);
        $company->status = 0;
        $company->save();

        Alert::success('SUCCESS', 'Deactivated Successfully.');
        return back();
    }

    public function activate($id) {
        $company = Company::findOrFail($id);
        $company->status = 1;
        $company->save();

        Alert::success('SUCCESS', 'Activated Successfully.');
        return back();
    }
}
