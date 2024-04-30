<?php

namespace App\DeptHead;

use App\Admin\DepartmentGroup;
use Illuminate\Database\Eloquent\Model;

class OnGoingInnovation extends Model
{
    protected $table = 'ongoing_innovations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'department_group_id',
        'innovation_projects',
        'current_status',
        'work_number',
        'start_date',
        'end_date',
        'date'
    ];
}
