<?php

namespace App\Admin;

use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\KpiScore;
use App\DeptHead\ProcessDevelopment;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'departments';

    protected $fillable = ['dept_code', 'dept_name', 'dept_head_id', 'target_date'];

    public function user() {
        return $this->belongsTo(User::class, 'dept_head_id');
    }

    public function kpi_scores() {
        return $this->hasMany(KpiScore::class);
    }

    public function process_development() {
        return $this->hasMany(ProcessDevelopment::class);
    }

    public function departmentKpi() {
        return $this->hasMany(departmentKpi::class);
    }

    public function departmentalGoals() {
        return $this->hasMany(DepartmentalGoals::class);
    }

    public function innovation() {
        return $this->hasMany(Innovation::class);
    }

    public function approver() {
        return $this->hasMany(Approve::class);
    }
}
