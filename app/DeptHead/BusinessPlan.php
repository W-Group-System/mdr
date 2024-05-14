<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessPlan extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
