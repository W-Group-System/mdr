<?php

namespace App\Approver;

use App\Admin\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MdrSummary extends Model
{
    protected $table = 'mdr_summary';

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
