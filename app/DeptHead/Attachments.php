<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Attachments extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdr_attachments';

    protected $primaryKey = 'id';

    protected $fillable = ['department_kpi_id', 'file_path', 'file_name'];
}
