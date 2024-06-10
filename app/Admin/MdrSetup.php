<?php

namespace App\Admin;

use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrAttachments;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MdrSetup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['department_id', 'name', 'target', 'mdr_group_id', 'date'];

    public function departmentGroup() {
        return $this->belongsTo(DepartmentGroup::class, 'mdr_group_id');
    }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departmentalGoals() {
        return $this->hasOne(DepartmentalGoals::class, 'mdr_setup_id');
    }

    public function attachments() {
        return $this->hasMany(Attachments::class, 'mdr_setup_id');
    }
}
