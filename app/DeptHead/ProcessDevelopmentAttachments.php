<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class ProcessDevelopmentAttachments extends Model
{
    protected $table = 'pd_attachments';

    protected $fillable = ['pd_id', 'filepath', 'filename'];
}
