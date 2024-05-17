<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MdrStatus extends Model
{
    protected $table = 'mdr_status';

    // protected $fillable = ['department_id', 'department_head_id', 'deadline', 'submission_date', 'status', 'approved_date', 'rate', 'remarks'];
    protected $fillable = ['status', 'start_date'];

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
