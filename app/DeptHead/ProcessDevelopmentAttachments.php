<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProcessDevelopmentAttachments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pd_attachments';

    protected $fillable = ['pd_id', 'filepath', 'filename'];
}
