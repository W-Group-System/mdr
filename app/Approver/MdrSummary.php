<?php

namespace App\Approver;

use App\Admin\Department;
use App\DeptHead\MdrStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MdrSummary extends Model
{
    protected $table = 'mdr_summary';

    protected $fillable = ['approved_date', 'rate'];

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mdrStatus() {
        return $this->hasMany(MdrStatus::class);
    }
}
