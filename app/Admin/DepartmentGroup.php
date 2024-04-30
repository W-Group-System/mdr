<?php

namespace App\Admin;

use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\OnGoingInnovation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepartmentGroup extends Model
{
    protected $table = 'department_kpi_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    public function departmentalGoals() {
        return $this->hasMany(DepartmentalGoals::class)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $this->yearAndMonth());
    }

    public function innovations() {
        return $this->hasMany(Innovation::class)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $this->yearAndMonth());
    }

    public function businessPlans() {
        return $this->hasMany(BusinessPlan::class)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $this->yearAndMonth());
    }

    public function ongoingInnovation() {
        return $this->hasMany(OnGoingInnovation::class, null, 'id')
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $this->yearAndMonth());
    }

    public function yearAndMonth() {
        $yearAndMonth = date('Y-m');

        return $yearAndMonth;
    }

    
}
