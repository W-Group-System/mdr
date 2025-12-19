<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Company;
use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\User;
use App\UserAccessModule;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index() {

        $userList = User::get();
        $company = Company::where('status',"Active")->get();
        $departmentList = Department::select('id', 'code', 'name')
            ->where('status', "Active")
            ->get();
        
        return view('admin.users', 
            array(
                'department' => $departmentList,
                'userList' => $userList,
                'company' => $company
            )
        );
    }

    public function addUserAccounts(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email',
            'password' => 'confirmed|min:6',
        ]);

        if ($validator->fails()) {
            
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $user = new User;
            $user->department_id = $request->department;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('wgroup123');
            $user->role = $request->role;
            $user->company_id = $request->company;
            $user->status = "Active";
            $user->save();

            Alert::success('Successfully Added')->persistent('Dismiss');
            return back();
        }
    }

    public function updateUserAccounts(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            // 'department' => 'required',
            'email' => 'email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $user = User::findOrFail($id);
            if ($user) {
                $user->name = $request->name;
                $user->department_id = $request->department;
                $user->email = $request->email;
                $user->role = $request->role;
                $user->company_id = $request->company;
                $user->save();
            }
            
            Alert::success('Successfully Updated')->persistent('Dismiss');
            return back();
        }
    }

    public function changeAccountStatus(Request $request) {
        $userData = User::findOrFail($request->id);

        if ($userData->status == "Active") {
            $userData->status = "Inactive";
            $userData->save();

            return array('status' => 0);
        }
        else {
            $userData->status = "Active";
            $userData->save();

            return array('status' => 1);
        }
    }

    public function changePassword(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'password' => 'min:6|confirmed'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $userData = User::findOrFail($id);

            if ($userData) {
                $userData->password = bcrypt($request->password);
                $userData->save();

                Alert::success('Your password has been changed')->persistent('Dismiss');
                return back();
            }
        }
        
    }

    public function userAccessModule($id)
    {
        $modules = Module::with('submodule')->get();
        $user = User::with('audit','access_module')->findOrFail($id);

        return view('admin.user_access_module', 
            array(
                'modules' => $modules,
                'user' => $user
            )
        );
    }

    public function storeAccessModule(Request $request)
    {
        // dd($request->all());
        $user_access_module = UserAccessModule::where('user_id', $request->user_id)->first();
        if ($user_access_module)
        {
            foreach($request->module_access as $moduleKey=>$module)
            {
                $user_access_module = UserAccessModule::where('user_id', $request->user_id)->where('module_id',$moduleKey)->first();
                foreach($module as $actionKey=>$value)
                {
                    if ($actionKey == "read")
                    {
                        $user_access_module->read = $value;
                    }
                    if ($actionKey == "create")
                    {
                        $user_access_module->create = $value;
                    }
                    if ($actionKey == "update")
                    {
                        $user_access_module->update = $value;
                    }
                    if ($actionKey == "delete")
                    {
                        $user_access_module->delete = $value;
                    }
                    $user_access_module->save();
                }
            }

            foreach($request->submodule_access as $submoduleKey=>$module)
            {
                $user_access_module = UserAccessModule::where('user_id', $request->user_id)->where('submodule_id',$submoduleKey)->first();
                foreach($module as $actionKey=>$value)
                {
                    if ($actionKey == "read")
                    {
                        $user_access_module->read = $value;
                    }
                    if ($actionKey == "create")
                    {
                        $user_access_module->create = $value;
                    }
                    if ($actionKey == "update")
                    {
                        $user_access_module->update = $value;
                    }
                    if ($actionKey == "delete")
                    {
                        $user_access_module->delete = $value;
                    }
                    $user_access_module->save();
                }
            }
        }
        else
        {
            foreach($request->module_access as $moduleKey=>$module)
            {
                $user_access_module = new UserAccessModule;
                $user_access_module->user_id = $request->user_id;
                $user_access_module->module_id = $moduleKey;
                foreach($module as $actionKey=>$value)
                {
                    if ($actionKey == "read")
                    {
                        $user_access_module->read = $value;
                    }
                    if ($actionKey == "create")
                    {
                        $user_access_module->create = $value;
                    }
                    if ($actionKey == "update")
                    {
                        $user_access_module->update = $value;
                    }
                    if ($actionKey == "delete")
                    {
                        $user_access_module->delete = $value;
                    }
                    $user_access_module->save();
                }
            }

            foreach($request->submodule_access as $submoduleKey=>$module)
            {
                $user_access_module = new UserAccessModule;
                $user_access_module->user_id = $request->user_id;
                $user_access_module->submodule_id = $submoduleKey;
                foreach($module as $actionKey=>$value)
                {
                    if ($actionKey == "read")
                    {
                        $user_access_module->read = $value;
                    }
                    if ($actionKey == "create")
                    {
                        $user_access_module->create = $value;
                    }
                    if ($actionKey == "update")
                    {
                        $user_access_module->update = $value;
                    }
                    if ($actionKey == "delete")
                    {
                        $user_access_module->delete = $value;
                    }
                    $user_access_module->save();
                }
            }
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
}
