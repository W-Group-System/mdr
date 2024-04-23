<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class DepartmentGroup extends Model
{
    protected $table = 'department_kpi_groups';

    protected $fillable = ['name'];
}
