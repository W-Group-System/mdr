<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MdrStatus extends Model
{
    protected $table = 'mdr_summary';

    protected $fillable = ['department_id', 'department_head_id', 'deadline', 'submission_date', 'status', 'approved_date', 'rate', 'remarks'];

    public $timestamp = false;

    public function department() {

        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function user() {

        return $this->hasOne(User::class, 'id', 'department_head_id');
    }
}
