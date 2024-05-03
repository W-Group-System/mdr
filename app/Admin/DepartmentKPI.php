<?php

namespace App\Admin;

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
}
