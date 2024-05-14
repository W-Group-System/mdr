<?php

namespace App\Admin;

use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrAttachments;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentKPI extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = "department_kpi";

    protected $fillable = ['department_id', 'name', 'target', 'department_group_id', 'date'];

    // public function department() {
    //     return $this->hasOne(Department::class);
    // }

    public function departmentGroup() {
        return $this->belongsTo(DepartmentGroup::class);
    }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departmentalGoals() {
        return $this->hasOne(DepartmentalGoals::class, 'department_kpi_id');
    }

    public function attachments() {
        return $this->hasMany(Attachments::class, 'department_kpi_id');
    }
}
