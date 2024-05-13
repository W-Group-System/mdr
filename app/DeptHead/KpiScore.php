<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class KpiScore extends Model
{
    protected $table = 'kpi_scores';

    protected $fillable = ['department_id', 'grade', 'rating', 'score', 'date', 'pd_scores', 'status_level', 'innovation_scores', 'final_approved'];
}
