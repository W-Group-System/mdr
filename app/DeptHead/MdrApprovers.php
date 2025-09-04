<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MdrApprovers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdr_approvers';

    public function users() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function departments() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function mdrRelationship()
    {
        return $this->belongsTo(Mdr::class, 'mdr_id', 'id');
    }
    public function siblingApprovers()
    {
        return $this->hasMany(MdrApprovers::class, 'mdr_id', 'mdr_id');
    }
}
