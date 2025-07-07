<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\Admin\MdrSetup;
use App\DepartmentKpi;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentalGoals extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'departmental_goals';

    public function departments() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function departmentKpi() 
    {
        return $this->belongsTo(DepartmentKpi::class, 'department_kpi_id');
    }
    public function attachments()
    {
        return $this->hasMany(Attachments::class);
    }
}
