<?php

namespace App\DeptHead;

use App\AcceptanceHistory;
use App\Admin\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Mdr extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function departments() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function mdrApprover()
    {
        return $this->hasMany(MdrApprovers::class);
    }
    public function departmentalGoals()
    {
        return $this->hasMany(DepartmentalGoals::class, 'mdr_id', 'id');
    }
    public function processImprovement()
    {
        return $this->hasMany(ProcessDevelopment::class);
    }
    public function innovation()
    {
        return $this->hasMany(Innovation::class, 'mdr_id', 'id');
    }
    public function mdrHistoryLogs()
    {
        return $this->hasMany(AcceptanceHistory::class);
    }
}
