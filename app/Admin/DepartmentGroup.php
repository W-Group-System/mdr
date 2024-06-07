<?php

namespace App\Admin;

use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\OnGoingInnovation;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentGroup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdr_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    public function departmentalGoals() {
        return $this->hasMany(DepartmentalGoals::class, 'mdr_group_id');
    }

    public function departmentKpi() {
        return $this->hasMany(DepartmentKPI::class, 'mdr_group_id');
    }

    public function processDevelopment() {
        return $this->hasMany(ProcessDevelopment::class, 'mdr_group_id');
    }

    public function innovation() {
        return $this->hasMany(Innovation::class, 'mdr_group_id');
    }
}
