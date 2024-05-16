<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class KpiScore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'kpi_scores';

    protected $fillable = ['department_id', 'grade', 'rating', 'score', 'date', 'pd_scores', 'status_level', 'innovation_scores', 'final_approved', 'total_rating', 'timeliness'];
}
