<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class InnovationAttachments extends Model
{
    protected $table = 'innovation_attachments';

    protected $fillable = ['department_id', 'department_group_id', 'innovation_id', 'filepath', 'filename', 'date'];
}
