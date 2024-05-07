<?php

namespace App\Admin;

use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrAttachments;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class DepartmentKPI extends Model
{
    protected $table = "department_kpi";

    protected $fillable = ['department_id', 'name', 'target', 'department_group_id', 'date'];

    public function department() {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function departmentGroup() {
        return $this->hasOne(DepartmentGroup::class, 'id', 'department_group_id');
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
