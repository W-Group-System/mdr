<?php

namespace App;

use App\Admin\Department;
use Illuminate\Database\Eloquent\Model;

class DepartmentKpi extends Model
{
    public function mdr_group()
    {
        return $this->belongsTo(DepartmentKpi::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
