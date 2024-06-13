<?php

namespace App\Approver;

use App\Admin\Department;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrScore;
use App\DeptHead\MdrStatus;
use App\HR\ForNod;
use App\HR\NodAttachments;
use App\HR\NteAttachments;
use App\HR\PipAttachments;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MdrSummary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'mdr_summary';

    protected $fillable = ['approved_date', 'rate', 'status_level', 'final_approved', 'submission_date', 'penalty_status'];

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
        return $this->hasOne(MdrScore::class, 'mdr_summary_id');
    }

    public function nteAttachments() {
        return $this->hasOne(NteAttachments::class);
    }

    public function nodAttachments() {
        return $this->hasOne(NodAttachments::class);
    }

    public function pipAttachments() {
        return $this->hasOne(PipAttachments::class);
    }
}
