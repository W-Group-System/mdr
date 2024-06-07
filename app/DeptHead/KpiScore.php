<?php

namespace App\DeptHead;

use App\Admin\Department;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class KpiScore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdr_scores';

    protected $fillable = ['department_id', 'grade', 'rating', 'score', 'date', 'pd_scores', 'status_level', 'innovation_scores', 'final_approved', 'total_rating', 'timeliness', 'mdr_summary_id'];

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
