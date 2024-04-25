<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class BusinessPlan extends Model
{
    protected $table = 'business_plans';

    protected $fillable = array(
        'department_id',
        'department_group_id',
        'activities',
        'isBasedOnPlanned',
        'proof_of_completion',
        'start_date',
        'end_date',
        'date'
    );
}
