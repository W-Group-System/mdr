<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\Admin\MdrSetup;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentalGoals extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'departmental_goals';

    protected $fillable = [
        'department_id', 
        'mdr_group_id', 
        'mdr_setup_id', 
        'kpi_name', 
        'target', 
        'grade', 
        'actual', 
        'remarks', 
        'year', 
        'month', 
        'deadline', 
        'status_level', 
        'final_approved',
        'mdr_summary_id'
    ];

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function mdrSetup() {
        return $this->belongsTo(MdrSetup::class, 'mdr_setup_id');
    }
}
