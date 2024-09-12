<?php

namespace App\DeptHead;

use App\Admin\Department;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProcessDevelopment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'process_development';

    protected $fillable = ['department_id', 'mdr_group_id', 'description', 'accomplished_date', 'status_level', 'final_approved', 'deadline', 'month', 'year', 'remarks'];

    public function pdAttachments() {
        return $this->hasMany(ProcessDevelopmentAttachments::class, 'process_development_id');
    }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
