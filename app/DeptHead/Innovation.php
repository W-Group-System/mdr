<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    protected $table = 'innovations';

    protected $fillable = ['department_group_id', 'department_id', 'projects', 'project_summary', 'work_order_number', 'start_date', 'end_date', 'actual_date', 'date'];
}
