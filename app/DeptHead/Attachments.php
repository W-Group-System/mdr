<?php

namespace App\DeptHead;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    protected $table = 'mdr_attachments';

    protected $primaryKey = 'id';

    protected $fillable = ['department_goals_id', 'file_path', 'file_name'];
}
