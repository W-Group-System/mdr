<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\Approver\MdrSummary;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MdrScore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function mdrSummary()
    {
        return $this->belongsTo(MdrSummary::class);
    }
}
