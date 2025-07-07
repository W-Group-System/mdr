<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Innovation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'innovations';

    protected $fillable = ['mdr_group_id', 'department_id', 'projects', 'project_summary', 'work_order_number', 'start_date', 'end_date', 'actual_date', 'date', 'status_level', 'final_approved', 'remarks'];

    public function innovationAttachments() 
    {
        return $this->hasMany(InnovationAttachments::class);
    }
}
