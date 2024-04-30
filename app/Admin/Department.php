<?php

namespace App\Admin;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['dept_code', 'dept_name', 'dept_head_id', 'target_date'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'dept_head_id');
    }
}
