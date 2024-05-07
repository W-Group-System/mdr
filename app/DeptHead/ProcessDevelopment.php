<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class ProcessDevelopment extends Model
{
    protected $table = 'process_development';

    protected $fillable = ['department_id', 'department_group_id', 'description', 'accomplished_date', 'status'];

    public function pd_attachments() {
        return $this->hasOne(ProcessDevelopmentAttachments::class, 'pd_id');
    }
}
