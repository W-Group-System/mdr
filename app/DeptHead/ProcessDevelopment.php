<?php

namespace App\DeptHead;

use App\Admin\Department;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProcessDevelopment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'process_development';

    protected $fillable = ['department_id', 'department_group_id', 'description', 'accomplished_date', 'status_level', 'final_approved'];

    public function pd_attachments() {
        return $this->hasOne(ProcessDevelopmentAttachments::class, 'pd_id');
    }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
