<?php

namespace App\Approver;

use App\Admin\Department;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\KpiScore;
use App\DeptHead\MdrStatus;
use App\HR\NteAttachments;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MdrSummary extends Model
{
    protected $table = 'mdr_summary';

    protected $fillable = ['approved_date', 'rate', 'status_level', 'final_approved', 'submission_date'];

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mdrStatus() {
        return $this->hasMany(MdrStatus::class);
    }

    public function kpiScores() {
        return $this->hasOne(KpiScore::class, 'mdr_summary_id');
    }

    public function nteAttachments() {
        return $this->hasOne(NteAttachments::class);
    }
}
