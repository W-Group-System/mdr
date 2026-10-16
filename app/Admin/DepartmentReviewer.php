<?php

namespace App\Admin;

use App\DeptHead\DepartmentalGoals;
use App\User;
use App\Admin\Company;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentReviewer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    public function companies()
    {
        return $this->belongsToMany(
            Company::class,
            'department_approver_companies', 
            'department_approver_id',         
            'company_id'                     
        );
    }
}
