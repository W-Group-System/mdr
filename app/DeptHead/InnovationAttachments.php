<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InnovationAttachments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'innovation_attachments';

    protected $fillable = ['department_id', 'mdr_group_id', 'innovation_id', 'filepath', 'filename', 'date'];
}
