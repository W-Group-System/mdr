<?php

namespace App\Admin;

use App\DeptHead\DepartmentalGoals;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentApprovers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function user() {
        return $this->belongsTo(User::class);
    }

}
