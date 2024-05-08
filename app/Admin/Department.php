<?php

namespace App\Admin;

use App\DeptHead\KpiScore;
use App\DeptHead\ProcessDevelopment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['dept_code', 'dept_name', 'dept_head_id', 'target_date'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'dept_head_id');
    }

    public function kpi_scores() {
        return $this->hasMany(KpiScore::class);
    }

    public function process_development() {
        return $this->hasMany(ProcessDevelopment::class);
    }
}
