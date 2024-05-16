<?php

namespace App\Admin;

use App\DeptHead\DepartmentalGoals;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Approve extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'manage_approvers';

    protected $fillable = ['user_id', 'status_level'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function dgStatusLevel() {
        return $this->hasMany(DepartmentalGoals::class, 'status_level', 'status_level');
    }
}
