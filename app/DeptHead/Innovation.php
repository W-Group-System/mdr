<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Innovation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'innovations';

    protected $fillable = ['department_group_id', 'department_id', 'projects', 'project_summary', 'work_order_number', 'start_date', 'end_date', 'actual_date', 'date', 'status_level', 'final_approved'];

    public function innovationAttachments() {
        return $this->hasMany(InnovationAttachments::class);
    }
}
