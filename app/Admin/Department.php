<?php

namespace App\Admin;

use App\Approver\MdrSummary;
use App\Approver\Warnings;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['code', 'name', 'user_id', 'target_date', 'status'];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function kpi_scores() 
    // {
    //     return $this->hasMany(MdrScore::class);
    // }
    // public function process_development() 
    // {
    //     return $this->hasMany(ProcessDevelopment::class);
    // }
    // public function mdrSetup() {
    //     return $this->hasMany(mdrSetup::class);
    // }
    // public function departmentalGoals() 
    // {
    //     return $this->hasMany(DepartmentalGoals::class);
    // }
    // public function innovation() 
    // {
    //     return $this->hasMany(Innovation::class);
    // }
    // public function approver() {
    //     return $this->hasMany(DepartmentApprovers::class);
    // }
    // public function mdrSummary() {
    //     return $this->hasMany(MdrSummary::class);
    // }
    // public function warnings() {
    //     return $this->hasMany(Warnings::class);
    // }
    // public function attachments() {
    //     return $this->hasMany(Attachments::class);
    // }
}
